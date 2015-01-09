<?php
/*
 * 1.  Super Admin Routes

 * 2.  Public  Routes
 *
 * 3.  Public Logged In Routes
 *
 * 4.  Public Logged In Ajax
 *
 * 5.  Editor Logged In Ajax Routes
 *
 * 6.  Editor Logged In Routes
 *
 */

Route::get('/', 'HomeController@index');

Route::group(array('before' => 'auth|force.ssl'), function(){
    Route::get('editors', 'HomeController@editors');
});

//////////////////////////////////
//                              //
// 1.  SuperAdmin Routes        //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl|superAuth'), function() {
    Route::any('admin/{action?}/{subAction?}/{id?}',function ($action = null, $subAction = null, $id = null) {
            $app = app();
            $adminController = $app->make('AdminController');

            $parameters = array(
                'data'  => array(
                    'default_tweakables'    => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
                    'tweakables'            => array(),
                    'action'                => $action,
                    'subAction'             => $subAction,
                    'id'                    => $id
                )
            );

            if($action == null){
                return $adminController->callAction('index', $parameters);
            }else{
                return $adminController->callAction($action, $parameters);
            }
        }
    );
});


//////////////////////////
//                      //
//   2. Public Routes   //
//                      //
//////////////////////////

////POST route for Bitbucket WebHook
//Route::any('/bitbucket/{token}', function($token){
//    $input = Input::get('payload');
//    $input = str_replace('\\"','"',$input);
//    $input = json_decode($input);
//    $log = '';
//    $msgs = '';
//
//    if(isset($input->commits) && $token == '5237239250'){
//        $commits = $input->commits;
//        $doPull = false;
//        foreach($commits as $commit) {
//            if ($commit->branch == 'dev') {
//                $doPull = true;
//                $msgs .= $commit->author . ' - ' . $commit->message;
//            }
//        }
//        if($doPull) {
//            $log .= "Automated git pull of branch dev on " . date("F j, Y, g:i a", strtotime('5 hours ago')) . "\n";
//            $log .= $msgs . "\n";
//            sleep(10);
//            shell_exec('chgrp -R webapps /web_content/share/mailAllSource');
//            shell_exec('chmod 775 -R /web_content/share/mailAllSource');
//            $log .= shell_exec('git pull origin dev');
//            shell_exec('chgrp -R webapps /web_content/share/mailAllSource');
//            shell_exec('chmod 775 -R /web_content/share/mailAllSource');
//        }
//    }
//
//    File::put('/web_content/share/mailAllSource/log.txt', $log);
//});

//Show logs
Route::get('/logs/{instanceName}/{fileName}', function($instanceName, $fileName){
    echo '/web_content/share/mailAllSource/public/logs/'.$instanceName.'/'.$fileName;die;
    return file_get_contents('/web_content/share/mailAllSource/public/logs/'.$instanceName.'/'.$fileName);
});

//Show live publication in stripped down reader
Route::get('/{instanceName}/', function($instanceName){

    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();


    $data = array(
        'instance'		=> $instance,
        'instanceId'	=> $instance->id,
        'instanceName'	=> $instance->name,
        'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
        'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
        'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
        'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        'isPublication'            => true
    );

    if(isset($data['tweakables']['publication-public-view']) && !$data['tweakables']['publication-public-view']){
        return Redirect::to('/')->withError('This publication does not have a public archive.');
    }else if(!$data['default_tweakables']['publication-public-view']){
        return Redirect::to('/')->withError('This publication does not have a public archive.');
    }

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

    if(Publication::where('instance_id',$instance->id)->where('published','Y')->count() > 0) {
        //Get most recent live publication
        $data['publication'] = Publication::where('instance_id', $instance->id)->livePublication()->first();
    }else{
        $data['publication'] = null;
    }

    return View::make('public.publication')->with($data);
});

//Show this article with share buttons and stuff
Route::get('/{instanceName}/article/{article_id}', function($instanceName, $article_id){
    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();

    $data = array(
        'instance'		=> $instance,
        'instanceId'	=> $instance->id,
        'instanceName'	=> $instance->name,
        'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
        'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
        'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
        'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        'isArticle'                => true
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

    //Get this publication
    $article = Article::where('instance_id',$instance->id)->where('id',$article_id)->firstOrFail();

    //Populate $data
    $data['article'] = $article;

    return View::make('public.article')->with($data);
});


//Show this publication in stripped down reader
Route::get('/{instanceName}/archive/{publication_id}', function($instanceName, $publication_id){
    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();

    if(Publication::where('instance_id',$instance->id)->published()->count() > 0){

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name')
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

        //Get this publication
        $publication = Publication::where('id', $publication_id)->published()->withArticles()->first();

        //Populate $data
        $data['publication'] = $publication;
    }

    return View::make('public.publication')->with($data);
});

//Show archives
Route::get('/{instanceName}/archive/', function($instanceName) {
    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();

    if(Publication::where('instance_id',$instance->id)->published()->count() > 0){

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        );

        //Setup dropdowns for searching
        $data['years'] = array('--' => '--');

        foreach($data['years'] as $index => $value){
            $index = $value;
        }

        foreach(range(date('Y'), date('Y')-10) as $year){
            $data['years'][$year] = $year;
        }

        $data['months'] = array(
            '--' => '--',
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
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
        $publications = Publication::where('instance_id',$instance->id)->published()->orderBy('publish_date','DESC')->paginate(15);

        //Populate $data
        $data['publications'] = $publications;

    }
    return View::make('public.archive')->with($data);
});

//Show search results for public users
Route::get('/{instanceName}/search', function($instanceName){
    $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();
    $data = array(
        'instance'		=> $instance,
        'instanceId'	=> $instance->id,
        'instanceName'	=> $instance->name,
        'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
        'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
        'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
        'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        'searchValue'              => Input::get('search'),
        'year'                     => Input::has('year') ? Input::get('year') : '--',
        'month'                    => Input::has('month') ? Input::get('month') : '--',
        'querySummary'             => ''
    );

    //Setup dropdowns for searching
    $data['years'] = array('--' => '--');

    foreach($data['years'] as $index => $value){
        $index = $value;
    }

    foreach(range(date('Y'), date('Y')-5) as $year){
        $data['years'][$year] = $year;
    }

    $data['months'] = array(
        '--' => '--',
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
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

    $data['articleResults'] = DB::table('article')
        ->join('publication_order', 'article.id', '=', 'publication_order.article_id')
        ->join('publication', 'publication_order.publication_id', '=', 'publication.id')
        ->select('article.id as article_id',
             'article.title as title',
             'article.updated_at as updated_at',
             'article.created_at as created_at',
             'publication.id as publication_id',
             'publication.published',
             'publication.publish_date'
        )->where('publication.instance_id', $instance->id);

    if($data['searchValue'] != ''){
        $data['articleResults'] = $data['articleResults']->where(function ($query) {
            $query->Where('article.title', 'LIKE', '%' . Input::get('search') . '%')
                ->orWhere('article.content', 'LIKE', '%' . Input::get('search') . '%');
        });
        $data['querySummary'] .= ' where the article contains <strong>' . $data['searchValue'] . '</strong> ';
    }

    if($data['year'] != '--' && $data['month'] == '--'){
        $data['articleResults'] = $data['articleResults']
            ->where('publication.publish_date','LIKE','%'.$data['year'].'%');
        $data['querySummary'] .= ' published in <strong>' . $data['year'] . '</strong>.';
    }elseif($data['year'] != '--' && $data['month'] != '--'){
        $data['articleResults'] = $data['articleResults']
            ->where('publication.publish_date','LIKE','%'.$data['year'].'-'.date('m',strtotime('2014-'.$data['month'].'-01')).'%');
        $data['querySummary'] .= ' published in <strong>' . $data['months'][$data['month']] . '</strong> of <strong>' . $data['year'] . '</strong>.';
    }

    $data['articleResults'] = $data['articleResults']->orderBy('publication.publish_date', 'DESC')->paginate(15);
    foreach($data['articleResults'] as $article){
        $thisArticle = Article::find($article->article_id);
        $article->original_publish_date = $thisArticle->originalPublishDate();
        $article->original_publication_id = $thisArticle->originalPublication();
    }

    return View::make('public.search')->with($data);
});

//Return image lists for ckeditors
Route::get('/json/{instanceName}/images', function($instanceName){
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
});



//////////////////////////////////
//                              //
// 3.  Public Logged In Routes  //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl'), function(){
    Route::get('/submit/{instanceName}', 'SubmissionController@index');
});

////////////////////////////////
//                            //
// 4.  Public Logged In Ajax  //
//                            //
////////////////////////////////
Route::group(array('before' => 'force.ssl|registerSubmitter'), function() {
    Route::resource('/resource/submission', 'SubmissionController');
});

//////////////////////////////////////
//                                  //
// 5.  Editor Logged In Ajax Routes //
//                                  //
//////////////////////////////////////

Route::group(array('before' => 'force.ssl'), function(){
    Route::post('/promote/{instanceName}/{submission_id}', 'SubmissionController@promoteSubmission');

    Route::resource('/resource/article', 'ArticleController');

    Route::resource('/resource/publication', 'PublicationController');

    Route::resource('/resource/image', 'ImageController');

    Route::post('/resource/publication/updateOrder/{publication_id}', 'PublicationController@updateOrder');

    Route::post('/resource/publication/updateType/{publication_id}', 'PublicationController@updateType');

    //Handle Article Carts
    //Add to cart
    Route::post('/cart/{instanceName}/add', function($instanceName){
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
    });

    //Remove from cart
    Route::post('/cart/{instanceName}/remove', function($instanceName){
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
    });

    Route::post('/cart/{instanceName}/clear', function($instanceName){
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
    });

    //Post routes so AJAX can grab editable regions
    Route::any('/editable/article/{article_id}/{publication_id?}', function($article_id, $publication_id = ''){
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
                'isEditable'               => true
            ,
                'shareIcons'               => false,
            );

            if($publication_id != ''){
                $data['publication'] = Publication::where('id', $publication_id)->first();
            }
            return View::make('article.article', $data);
        });

    Route::get('admin/login', array('before' => 'force.ssl', function(){
            $name = 'mailAllSession';

            $date = date('Y-m-d');

            $value = md5('mailAll500P3RS3kR3T'.$date);

            return Redirect::to('/')->withCookie(Cookie::make($name, $value,time()+3600*2*1));
        }));

    Route::get('admin/logout', function(){
            return Redirect::guest('/')->withCookie(Cookie::forget('urlSession'));
        });
});



//////////////////////////////////
//                              //
// 6.  Editor Logged In Routes  //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl|editAuth'), function(){
    Route::get('/edit/{instanceName}/{action?}/{subAction?}', function($instanceName, $action = null, $subAction = null) {
        $app = app();
        $editorController = $app->make('EditorController');

        //Fetch Instance out of DB
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();

        //Stuff parameters into array
        $parameters = array(
            'subAction' => $subAction,
            'data'      => array()
        );

        $defaultTweakable = DefaultTweakable::all();

        //Gather Data Common to all editor views
        $parameters['data'] = array(
            'action'                   => $action,
            'subAction'                => $subAction,
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray($defaultTweakable, 'parameter', 'value'),
            'tweakables_types'         => reindexArray($defaultTweakable, 'parameter', 'type'),
            'default_tweakables_names' => reindexArray($defaultTweakable, 'parameter', 'display_name'),
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

    //Specific Saving Controller for things in the editor like saving settings
    Route::get('/deleteProfile/{instanceName}/{profileName}', 'EditorController@deleteProfile');

    //Specific Saving Controller for things in the editor like saving settings
    Route::get('/loadProfile/{instanceName}/{profileName}', 'EditorController@loadProfile');

    //Specific Saving Controller for things in the editor like saving settings
    Route::post('/save/{instanceName}/{action}', 'EditorController@save');

    //Perform and send a mail merge
    Route::any('mergeEmail/{instanceName}/{publication_id}', 'EmailController@mergeEmail');

    //Send an email
    Route::any('sendEmail/{instanceName}/{publication_id}', 'EmailController@sendEmail');
});

