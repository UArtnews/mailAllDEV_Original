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


//Show live publication in stripped down reader  (Eventually this will be the live homepage for each publication)
Route::get('/{instanceName}/', function($instanceName){

	//Fetch Instance out of DB
	$instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

	if(Publication::where('instance_id',$instance->id)->where('published','Y')->count() > 0){

		$data = array(
			'instance'		=> $instance,
			'instanceId'	=> $instance->id,
			'instanceName'	=> $instance->name,
			'tweakables'	=> ArrayTools::reindexArray($instance->tweakables()->get(), 'parameter'),
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

Route::resource('publications', 'PublicationsController');

Route::resource('articles', 'ArticlesController');

Route::resource('articles', 'ArticlesController');