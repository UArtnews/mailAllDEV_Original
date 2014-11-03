<?php
/*
 * 1.  Public  Routes
 *
 * 2.  Public Logged In Routes
 *
 * 3.  Public Logged In Ajax
 *
 * 4.  Editor Logged In Ajax Routes
 *
 * 5.  Editor Logged In Routes
 */

//This one will eventually go away or change drastically
//Route::get('/', 'HomeController@index');
Route::get('/', function(){
    $output = '';
    foreach(apache_get_modules() as $module){
        $output .= $module . "<br/>";
    }
    return $output;
});

//////////////////////////
//                      //
//   1. Public Routes   //
//                      //
//////////////////////////

//POST route for Bitbucket WebHook
Route::any('/bitbucket/{token}', function($token){
    $input = Input::get('payload');
    $input = str_replace('\\"','"',$input);
    $log = "Log Header \n\n";
    $input = json_decode($input);
    $stuff = '';
    foreach($input['commits'] as $name => $value){
        $stuff .= $name ."\n";
    }
    File::put('/web_content/share/mailAllSource/input.json', $stuff);
    if(isset($input['commits']) && $token == '5237239250'){
        $log .= "Payload Recieved:\n";
        $commits = $input['commits'];
        $doPull = false;
        foreach($commits as $commit) {
            if ($commit['branch'] == 'dev') {
                $doPull = true;
            }
        }

        if($doPull) {
            $log .= "Doing Pull!\n";
            //return shell_exec('git pull origin dev');
        }
    }else {
        $log .= "Incorrect token or no commits made!\n";
        //return 'HAHA, NOPE!';
    }
    File::put('/web_content/share/mailAllSource/log.json', $log);
});

//Show live publication in stripped down reader
Route::get('/{instanceName}/', function($instanceName){

    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();


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
        $data['publication'] = Publication::where('instance_id', $instance->id)->
        where('published', 'Y')->
        where('type', 'regular')->
        orderBy('publish_date', 'desc')->
        with(
            array(
                'articles' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            )
        )->
        first();
    }else{
        $data['publication'] = null;
    }

    return View::make('public.publication')->with($data);
});

//Show this article with share buttons and stuff
Route::get('/{instanceName}/article/{article_id}', function($instanceName, $article_id){
    //Fetch Instance out of DB
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

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
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

    if(Publication::where('instance_id',$instance->id)->where('published','Y')->count() > 0){

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
        $publication = Publication::where('id', $publication_id)->where('published','Y')->with(array('articles' => function($query){
                $query->orderBy('order', 'asc');
            }))->first();

        //Populate $data
        $data['publication'] = $publication;
    }

    return View::make('public.publication')->with($data);
});

//Show archives
Route::get('/{instanceName}/archive/', function($instanceName) {
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

        //Get some pubs
        $publications = Publication::where('instance_id',$instance->id)->where('published','Y')->orderBy('publish_date','DESC')->paginate(15);

        //Populate $data
        $data['publications'] = $publications;

    }
    return View::make('public.archive')->with($data);
});

//Show search results for public users
Route::get('/{instanceName}/search', function($instanceName){
    $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();
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

//////////////////////////////////
//                              //
// 2.  Public Logged In Routes  //
//                              //
//////////////////////////////////

Route::get('/submit/{instanceName}', 'SubmissionController@index');

////////////////////////////////
//                            //
// 3.  Public Logged In Ajax  //
//                            //
////////////////////////////////

//////////////////////////////////////
//                                  //
// 5.  Editor Logged In Ajax Routes //
//                                  //
//////////////////////////////////////

Route::post('/promote/{instanceName}/{submission_id}', 'SubmissionController@promoteSubmission');

Route::resource('/resource/article', 'ArticleController');

Route::resource('/resource/publication', 'PublicationController');

Route::resource('/resource/image', 'ImageController');

Route::resource('/resource/submission', 'SubmissionController');

Route::post('/resource/publication/updateOrder/{publication_id}', 'PublicationController@updateOrder');

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

//////////////////////////////////
//                              //
// 5.  Editor Logged In Routes  //
//                              //
//////////////////////////////////
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

//Specific Saving Controller for things in the editor like saving settings
Route::post('/save/{instanceName}/{action}', 'EditorController@save');

//Fire off an email
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


        $data['isEmail'] = false;
        return View::make('emailPublication', $data)->withMessage('Email Sent Successfully!');
    });