<?php

class PublicController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
        $data['publication_id'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';
        //Fetch Instance out of DB
        $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

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

            //Get most recent live publication
            $publication = Publication::where('instance_id',$instance->id)->where('published','Y')->orderBy('promoted')->orderBy('publish_date','desc')->first();

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
        $article = new Article;

        $article->instance_id = Input::get('instance_id');
        $article->title = Input::get('title');
        $article->content = Input::get('content');
        $article->author_id = '1';
        $article->published = 'N';

        $article->save();

        return Response::json(array('success' => 'New Article Saved Successfully'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $article = Article::findOrFail($id);

        return Response::json($article);
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
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $article = Article::findOrFail($id);
        $article->instance_id = Input::get('instance_id');
        $article->title = Input::get('title');
        $article->content = Input::get('content');
        $article->save();

        return Response::json(array('success' => 'Article Saved Successfully'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$article = Article::findOrFail($id);

        $article->delete();

        return Response::json(array('success' => 'Article Deleted Successfully'));
    }

}