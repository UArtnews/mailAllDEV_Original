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
    Route::any('admin/{action?}/{subAction?}/{id?}', 'AdminController@route');
});


//////////////////////////
//                      //
//   2. Public Routes   //
//                      //
//////////////////////////

//Show logs
Route::get('/logs/{instanceName}/{fileName}', 'MiscController@showLogs');

//Show live publication in stripped down reader
Route::get('/{instanceName}/', 'PublicController@showPublicHome');

//Show this article with share buttons and stuff
Route::get('/{instanceName}/article/{article_id}', 'PublicController@showArticle');

//Show this publication in stripped down reader
Route::get('/{instanceName}/archive/{publication_id}', 'PublicController@showPublication');

//Show archives
Route::get('/{instanceName}/archive/', 'PublicController@showArchive');

//Show search results for public users
Route::get('/{instanceName}/search', 'PublicController@search');

//Return image lists for ckeditors
Route::get('/json/{instanceName}/images', 'MiscController@imageJSON');

//////////////////////////////////
//                              //
// 3.  Public Logged In Routes  //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl'), function(){
    Route::get('/presubmit/{instanceName}', 'SubmissionController@preSubmit');
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
    Route::post('/cart/{instanceName}/add', 'MiscController@cartAdd');

    //Remove from cart
    Route::post('/cart/{instanceName}/remove', 'MiscController@cartRemove');

    Route::post('/cart/{instanceName}/clear', 'MiscController@cartClear');

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

