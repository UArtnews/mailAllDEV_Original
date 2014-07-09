<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

//Default Editor
Route::get('/edit/{instanceName}', 'EditorController@index');

//Specific Action Editor
Route::get('/edit/{instanceName}/{action}', 'EditorController@index');

//Specific Action + Sub-Action Editor
Route::get('/edit/{instanceName}/{action}/{subAction}', 'EditorController@index');

//Specific Saving Controller
Route::post('/save/{instanceName}/{action}', 'EditorController@save');

Route::resource('/resource/article', 'ArticleController');

Route::resource('/resource/publication', 'PublicationController');

Route::resource('/resource/image', 'ImageController');

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

		//Get most recent live publication
		$publication = Publication::where('instance_id',$instance->id)->where('published','Y')->orderBy('publish_date','desc')->first();
		
		$articles = array();

		//Get the article order array and grab the articles
		$articleArray = json_decode($publication->article_order);
		
		foreach($articleArray as $articleID){
			array_push($articles, Article::find($articleID));
		}

		//Populate $data
		$data['publication'] = $publication;
		$data['publication']->articles = $articles;
	}

	return View::make('publication')->with($data);
});