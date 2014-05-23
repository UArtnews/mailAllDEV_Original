<?php

class EditorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//Grab Instance Name from URI
		$instanceName =  urldecode(Request::segment(2));

		//Grab Action Type from URI
		$action =  urldecode(Request::segment(3));

		//Fetch Instance out of DB
		$instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

		$data = array(
			'instance'		=> $instance,
			'instanceId'	=> $instance->id,
			'instanceName'	=> $instance->name,
			'action'		=> $action,
			'tweakables'	=> ArrayTools::reindexArray($instance->tweakables()->get(), 'parameter'),
		);

		if($action == 'articles'){
			$data['articles'] = Article::where('instance_id',$instance->id)->get();
		}elseif($action == 'publications'){
			$data['publications'] = Publication::where('instance_id', $instance->id)->get();
			foreach($data['publications'] as $publication){
				$data['publications']->articles = array();
				$articleArray = json_decode($publication->article_order);
				
				foreach($articleArray as $articleID){
					array_push($data['publications']->articles, Article::find($articleID));
				}
			}
		}elseif($action == 'images'){
			
		}else{
			//Get most recent live publication
			$publication = Publication::where('instance_id',$instance->id)->
                where('published','Y')->
                orderBy('publish_date','desc')->first();
			
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


		return View::make('editor', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.R
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}