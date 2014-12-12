<?php

class EmailController extends \BaseController
{
    private $excel;

    function __constructor(){
        $this->excel = App::make('ExcelGet');
    }


    public function sendEmail($instanceName, $publication_id){

        $instance = Instance::where('name', $instanceName)->first();

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isEditable'               => false,
            'shareIcons'               => false,
        );

        //Get This Publication
        $publication = Publication::where('id', $publication_id)->
        where('instance_id', $instance->id)->
        with(array('articles' => function($query){
                $query->orderBy('order', 'asc');
            }))->first();

        $data['publication'] = $publication;
        $data['isEmail'] = true;

        $html = View::make('emailPublication', $data)->render();
        $css = View::make('emailStyle', $data)->render();

        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
        $inliner->setHTML($html);
        $inliner->setCSS($css);

        $inlineHTML = $inliner->convert();

        if(Input::has('addressTo') && Input::has('addressFrom')){
            Mail::send('html', array('html' => $inlineHTML), function($message){
                    $message->to(Input::get('addressTo'))
                        ->subject(Input::has('subject') ? Input::get('subject') : '')
                        ->from(Input::get('addressFrom'), Input::has('nameFrom') ? Input::get('nameFrom') : '');
                });

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
            return Redirect::back()->withSuccess("Publication successfully sent!");
        }else{
            return Redirect::back()->withError("An error occurred, publication was not sent");
        }
    }

    public function mergeEmail($instanceName, $publication_id){
        set_time_limit(600);
        $instance = Instance::where('name', $instanceName)->first();

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isEditable'               => false,
            'shareIcons'               => false,
        );

        //Get This Publication
        $publication = Publication::where('id', $publication_id)->
        where('instance_id', $instance->id)->
        with(array('articles' => function($query){
                $query->orderBy('order', 'asc');
            }))->first();

        $data['publication'] = $publication;
        $data['isEmail'] = true;

        $mergeFileName = '';

        //Do File Upload, store as latestMerge.xlsx/xls
        if(Input::hasFile('mergeFile')){
            if(Input::file('mergeFile')->isValid() && ( Input::file('mergeFile')->getClientOriginalExtension() == 'xls' || Input::file('mergeFile')->getClientOriginalExtension() == 'xlsx' ) ){
                $mergePath = "/web_content/share/mailAllSource/docs/" . $instance->name;
                if(!file_exists($mergePath)){
                    mkdir($mergePath);
                }
                $mergeFileName = "latestMerge.".Input::file('mergeFile')->getClientOriginalExtension();
                if(file_exists($mergePath . "/" . $mergeFileName)){
                    unlink($mergePath . "/" . $mergeFileName);
                }
                Input::file('mergeFile', 0775)->move($mergePath, $mergeFileName);
            }else{
                return Redirect::back()->withError('Invalid Merge File Uploaded. XLS or XLSX files only!');
            }
        }else{
            return Redirect::back()->withError('No Merge File Uploaded!');
        }

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
        $mergedHTML = '';
        $sentCount = 0;
        $failCount = 0;
        if(Input::has('isTest')){
            //Do a single merge
            $mergedHTML = $inlineHTML;
            foreach($this->excel->oneRow($mergePath . "/" . $mergeFileName) as $index => $value){
                $pattern = '**' . $index . '**';
                $mergedHTML = str_replace($pattern, $value, $mergedHTML);
            }
            if(Input::has('testTo') && Input::has('addressFrom')){
                Mail::send('html', array('html' => $mergedHTML), function($message){
                        $message->to(Input::get('testTo'))
                            ->subject(Input::has('subject') ? Input::get('subject') : '')
                            ->from(Input::get('addressFrom'), Input::has('nameFrom') ? Input::get('nameFrom') : '');
                    });
                $data['success'] = true;
            }else{
                $data['error'] = true;
            }
        }else{
            //Do the big-daddy merge
            $addresses = $this->excel->toArray($mergePath . "/" . $mergeFileName);
            foreach($addresses as $address) {
                $addressField = Input::get('addressField');
                $addressTo = $address[$addressField];
                $mergedHTML = $inlineHTML;
                foreach ($address as $index => $value) {
                    $pattern = '**' . $index . '**';
                    $mergedHTML = str_replace($pattern, $value, $mergedHTML);
                }

                if (Input::has('addressFrom')) {
                    $validator = Validator::make(array('email' => $addressTo), array('email' => 'email|required'));
                    if($validator->fails()){
                        $data['error'] = true;
                        $failCount++;
                    }else{
                        $sentCount++;
                        Mail::send('html',array('html' => $mergedHTML),function ($message) use($addressTo) {
                                $message->to($addressTo)
                                    ->subject(Input::has('subject') ? Input::get('subject') : '')
                                    ->from(
                                        Input::get('addressFrom'),
                                        Input::has('nameFrom') ? Input::get('nameFrom') : ''
                                    );
                            }
                        );
                        $data['success'] = true;
                    }
                } else {
                    $failCount++;
                    $data['error'] = true;
                }
            }
        }

        //Display the results of the last email, might as well, it'll be merged
        $data['isEmail'] = true;
        if($sentCount > 0 && $failCount > 0){
            return Redirect::back()->withSuccess("$sentCount messages successfully sent!")->withError("$failCount messages encountered an error!");
        }elseif($sentCount > 0){
            return Redirect::back()->withSuccess("$sentCount messages successfully sent!");
        }elseif($failCount > 0){
            return Redirect::back()->withError("$failCount messages encountered an error!");
        }else{
            return Redirect::back()->withSuccess("Message successfully sent!");
        }
    }

}