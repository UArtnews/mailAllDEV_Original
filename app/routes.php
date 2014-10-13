<?php
Route::get('/', 'HomeController@index');
Route::get('/submit/{instanceName}', 'SubmissionController@index');

//Editor Routing
Route::get('/edit/{instanceName}/{action?}/{subAction?}', function($instanceName, $action = null, $subAction = null) {
    $app = app();
    $editorController = $app->make('EditorController');

    //Fetch Instance out of DB
    $instance = Instance::where('name', $instanceName)->firstOrFail();

    //Stuff parameters into array
    $parameters = array(
        'subAction' => $subAction,
        'data'      => array()
    );

    //Gather Data Common to all editor views
    $parameters['data'] = array(
        'action'                   => $action,
        'subAction'                => $subAction,
        'instance'                 => $instance,
        'instanceId'               => $instance->id,
        'instanceName'             => $instance->name,
        'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
        'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
        'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
        'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
    );

    //Stuff session data into data parameter
    if (Session::has('cart')) {
        $cart = Session::get('cart');

        if (isset($cart[$instance->id])) {
            $parameters['data']['cart'] = $cart[$instance->id];
        }
    }

    //Stuff tweakables into data parameter
    if (isset($parameters['data']['tweakables']['global-accepts-submissions'])) {
        if ($parameters['data']['tweakables']['global-accepts-submissions']) {
            $parameters['data']['submission'] = true;
        } else {
            $parameters['data']['submission'] = false;
        }
    } else {
        if ($parameters['data']['default_tweakables']['global-accepts-submissions']) {
            $parameters['data']['submission'] = true;
        } else {
            $parameters['data']['submission'] = false;
        }
    }

    //Route to correct method in EditorController
    //Default Editor Route
    if($action == null){
        return $editorController->callAction('index', $parameters);
    }else{
        return $editorController->callAction($action, $parameters);
    }
});

//Specific Saving Controller
Route::post('/save/{instanceName}/{action}', 'EditorController@save');

Route::post('/promote/{instanceName}/{submission_id}', 'SubmissionController@promoteSubmission');

Route::resource('/resource/article', 'ArticleController');

Route::resource('/resource/publication', 'PublicationController');

Route::resource('/resource/image', 'ImageController');

Route::resource('/resource/submission', 'SubmissionController');

Route::post('/resource/publication/updateOrder/{publication_id}', 'PublicationController@updateOrder');

//Handle Article Carts
//Add to cart
Route::post('/cart/{instanceName}/add', function($instanceName){
    $instance = Instance::where('name',urldecode($instanceName))->first();
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
});

//Remove from cart
Route::post('/cart/{instanceName}/remove', function($instanceName){
    $instance = Instance::where('name',urldecode($instanceName))->first();
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
});

Route::post('/cart/{instanceName}/clear', function($instanceName){
    $instance = Instance::where('name',urldecode($instanceName))->first();

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
});

//Post routes so AJAX can grab editable regions
Route::any('/editable/article/{article_id}', function($article_id){
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
        'article'                  => $article
    );

    return View::make('publication.editableWebArticle', $data);
});

//Return image lists for ckeditors
Route::get('/json/{instanceName}/images', function($instanceName){
    $instance = Instance::where('name',urldecode($instanceName))->first();

    //Grab all the images for that instance and send them to the user
    $images = array();
    foreach(Image::where('instance_id',$instance->id)->get() as $image){
        array_push($images,array(
            'image'  => URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename),
        ));
    }
    return Response::json($images);
});

//Show search results for public users
Route::get('/{instanceName}/search', function($instanceName)
{
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();
    $data = array(
        'instance'		=> $instance,
        'instanceId'	=> $instance->id,
        'instanceName'	=> $instance->name,
        'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
        'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
        'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
        'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
    );

    if(isset($data['tweakables']['global-accepts-submissions'])){
        if($data['tweakables']['global-accepts-submissions']){
            $data['submission'] = true;
        }else{
            $data['submission'] = false;
        }
    }else{
        if($data['default_tweakables']['global-accepts-submissions']){
            $data['submission'] = true;
        }else{
            $data['submission'] = false;
        }
    }

    //Get Articles which we'll find the pubs with
    $data['articleResults'] = Article::where('instance_id', $instance->id)
        ->where(function($query)
        {
            $query->Where('title','LIKE','%'.Input::get('search').'%')
                ->orWhere('content','LIKE','%'.Input::get('search').'%');
        })->get();

    //If we returned articles, go find their publications
    if(count($data['articleResults']) > 0){
        //Create array of article ID's for looking up publications
        $articleArray = array();
        foreach($data['articleResults'] as $articleResult){
            //make an array of all article id's
            array_push($articleArray, $articleResult->id);
        }

        //Get Publications where Articles Appear
        $data['publicationResults'] = DB::table('publication')
            ->join('publication_order','publication.id','=','publication_order.publication_id')
            ->whereIn('publication_order.article_id',$articleArray)
            ->where('publication.published','Y' )
            ->groupBy('publication.id')
            ->get();
    }else{
        //Didn't find any, return empty array
        $data['publicationResults'] = array();
    }

    return View::make('publication.publicSearch')->with($data);
});

//Show live publication in stripped down reader  (Eventually this will be the live homepage for each publication)
Route::get('/{instanceName}/', function($instanceName)
{

    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

    if(Publication::where('instance_id',$instance->id)->where('published','Y')->count() > 0){

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        );

        if(isset($data['tweakables']['global-accepts-submissions'])){
            if($data['tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }else{
            if($data['default_tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }

        //Get most recent live publication
        $publication = Publication::with('articles')->where('instance_id',$instance->id)->where('published','Y')->orderBy('publish_date','desc')->first();

        //Populate $data
        $data['publication'] = $publication;
    }

    return View::make('publication')->with($data);
});

//Show archives
Route::get('/{instanceName}/archive/{publication_id?}', function($instanceName, $publication_id = null)
{

    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

    if(Publication::where('instance_id',$instance->id)->where('published','Y')->count() > 0){

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        );

        if(isset($data['tweakables']['global-accepts-submissions'])){
            if($data['tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }else{
            if($data['default_tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }

        //Get some pubs
        $publications = Publication::with('articles')->where('instance_id',$instance->id)->where('published','Y')->orderBy('publish_date','DESC')->paginate(15);

        $data['directIsLoaded'] = false;

        foreach($publications as $publication){
            if($publication->id == $publication_id){
                $data['directIsLoaded'] = true;
                $data['publication_id'] = $publication_id;
            }
        }

        if(!$data['directIsLoaded'] && $publication_id){
            $data['directPublication'] = Publication::findOrFail($publication_id);
            $data['publication_id'] = $publication_id;
        }


        //Populate $data
        $data['publications'] = $publications;

    }

    return View::make('archive')->with($data);
});

Route::any('sendEmail/{instanceName}/{publication_id}', function($instanceName, $publication_id){

    $instance = Instance::where('name', $instanceName)->first();

    $data = array(
        'instance'		=> $instance,
        'instanceId'	=> $instance->id,
        'instanceName'	=> $instance->name,
        'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
        'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
        'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
        'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
    );

    //Get This Publication
    $publication = Publication::with('articles')->find($publication_id);
    $data['publication'] = $publication;
    $data['isEmail'] = true;


    //Publish if this is a real deal publish things
    if(!Input::has('isTest')){
        foreach($publication->articles as $article){
            $thisArticle = Article::find($article->id);
            $thisArticle->published = 'Y';
            $thisArticle->save();
        }

        $publication->published = 'Y';
        $publication->save();
    }

    $html = View::make('emailPublication', $data)->render();
    $css = View::make('emailStyle', $data)->render();

    $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
    $inliner->setHTML($html);
    $inliner->setCSS($css);

    $inlineHTML = $inliner->convert();

        echo $inlineHTML;die;

    if(Input::has('addressTo') && Input::has('addressFrom')){
        Mail::send('html', array('html' => $inlineHTML), function($message){
            $message->to(Input::get('addressTo'))
                ->subject(Input::has('subject') ? Input::get('subject') : '')
                ->from(Input::get('addressFrom'), Input::has('nameFrom') ? Input::get('nameFrom') : '');
        });
        $data['success'] = true;
    }else{
        $data['error'] = true;
    }


    $data['insertCss'] = true;
    $data['isEmail'] = false;
    return View::make('emailPublication', $data);
});

