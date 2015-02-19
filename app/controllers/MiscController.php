<?php

class MiscController extends BaseController {

    public function showLogs($instanceName, $fileName){
        echo '/web_content/share/mailAllSource/public/logs/'.$instanceName.'/'.$fileName;die;
        return file_get_contents('/web_content/share/mailAllSource/public/logs/'.$instanceName.'/'.$fileName);
    }

    public function imageJSON($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        //Grab all the images for that instance and send them to the user
        $images = array();
        foreach(Image::where('instance_id',$instance->id)->orderBy('created_at', 'desc')->get() as $image){
            $imageLocation = str_replace('https','http', URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename));
            array_push($images,array(
                'image'  => $imageLocation,
            ));
        }
        return Response::json($images);
    }

    public function cartAdd($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        $article_id = Input::get('article_id');

        if(Session::has('cart')){
            $cart = Session::get('cart');

            if(isset($cart[$instance->id])){
                if(isset($cart[$instance->id][$article_id])){
                    return Response::json(array(
                        'error'  => 'Article already in cart',
                        'cart'   => $cart[$instance->id]
                    ));
                }else{
                    $cart[$instance->id][$article_id] = Article::findOrFail($article_id)->title;
                    Session::put('cart', $cart);
                    return Response::json(array(
                        'success'   => 'Article added to cart',
                        'cart'      => $cart[$instance->id]
                    ));
                }
            }else{
                $cart[$instance->id][$article_id] = Article::findOrFail($article_id)->title;
                Session::put('cart', $cart);
                return Response::json(array(
                    'success'   => 'Article added to cart',
                    'cart'      => $cart[$instance->id]
                ));
            }
        }else{
            $cart = array();
            $cart[$instance->id][$article_id] = Article::findOrFail($article_id)->title;
            Session::put('cart', $cart);
            return Response::json(array(
                'success'   => 'Article added to cart',
                'cart'      => $cart[$instance->id]
            ));
        }
    }

    public function cartRemove($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        $article_id = Input::get('article_id');

        if(Session::has('cart')){
            $cart = Session::get('cart');

            if(isset($cart[$instance->id])){
                if(isset($cart[$instance->id][$article_id])){
                    unset($cart[$instance->id][$article_id]);
                    Session::put('cart', $cart);
                    return Response::json(array(
                        'success'  => 'Article removed from cart',
                        'cart'   => $cart[$instance->id]
                    ));
                }else{
                    return Response::json(array(
                        'error'   => 'Article not in cart',
                        'cart'      => $cart[$instance->id]
                    ));
                }
            }else{
                return Response::json(array(
                    'error'   => 'Article not in cart.',
                    'cart'      => array()
                ));
            }
        }else{
            return Response::json(array(
                'error'   => 'Article not in cart ',
                'cart'      => array()
            ));
        }
    }

    public function cartClear($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();

        if(Session::has('cart')){
            $cart = Session::get('cart');

            if(isset($cart[$instance->id])){
                unset($cart[$instance->id]);
                Session::put('cart',$cart);
                return Response::json(array(
                    'success'   => 'Cart cleared'
                ));
            }else{
                return Response::json(array(
                    'error' => 'Cart already empty'
                ));
            }
        }
    }

    public function articleAJAX($article_id, $publication_id = ''){
        $article = Article::findOrFail($article_id);
        //Grab instance ID from article

        $instanceId = $article->instance_id;

        $instance = Instance::findOrFail($instanceId);

        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'article'                  => $article,
            'isRepeat'                 => $article->isPublished($publication_id) ? true : false,
            'hideRepeat'               => $article->isPublished($publication_id) ? true : false,
            'isEmail'                  => false,
            'isEditable'               => true,
            'shareIcons'               => false,
        );

        if($publication_id != ''){
            $data['publication'] = Publication::where('id', $publication_id)->first();
        }
        return View::make('article.article', $data);
    }
}