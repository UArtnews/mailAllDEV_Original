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
        $instanceName = urldecode(Request::segment(2));

        //Grab Action Type from URI
        $action = urldecode(Request::segment(3));

        //Fetch Instance out of DB
        $instance = Instance::where('name', strtolower($instanceName))->firstOrFail();

        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'action'                   => $action,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
        );

        if ($action == 'articles')
        {
            $data['articles'] = Article::where('instance_id', $instance->id)->orderBy('created_at', 'desc')->paginate(15);

            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';

            if($data['subAction'] != ''){
                $data['directArticle'] = Article::findOrFail($data['subAction']);
            }

        } elseif ($action == 'publications')
        {
            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';

            $data['publications'] = Publication::where('instance_id', $instance->id)->orderBy('publish_date', 'desc')->has('articles')->paginate(15);

            if($data['subAction'] != ''){
                $data['directPublication'] = Publication::has('articles')->findOrFail($data['subAction']);
                $data['directIsLoaded'] = false;
                $articleArray = json_decode($data['directPublication']->article_order);
                $articles = array();

                //Check if this publication will be loaded and can be shortcut
                foreach ($data['publications'] as $publication)
                {
                    if($publication->id == $data['subAction']){
                        $data['directIsLoaded'] = true;
                    }
                }

                $data['directPublication']['articles'] = $articles;
            }

        } elseif ($action == 'images')
        {
            //Do image listing and upload
            $data['images'] = Image::where('instance_id',$instance->id)->orderBy('created_at','DESC')->get();


        } elseif ($action == 'settings')
        {
            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : 'appearanceTweakables';

            $data['appearanceTweakables'] = array(
                'global-background-color',
                'publication-background-color',
                'publication-border-color',
                'publication-h1-color',
                'publication-h1-font-size',
                'publication-h1-line-height',
                'publication-h2-color',
                'publication-h2-font-size',
                'publication-h2-line-height',
                'publication-h3-color',
                'publication-h3-font-size',
                'publication-h3-line-height',
                'publication-h4-color',
                'publication-h4-font-size',
                'publication-h4-line-height',
                'publication-p-color',
                'publication-p-font-size',
                'publication-p-line-height',
            );

            $data['contentStructureTweakables'] = array(
                'publication-banner-image',
                'publication-width',
                'publication-padding',
                'publication-hr-articles',
                'publication-hr-titles',
                'publication-repeated-items',
                'publication-headline-summary',
                'publication-headline-summary-position',
            );

            $data['headerFooterTweakables'] = array(
                'publication-header',
                'publication-footer',
            );

        }elseif($action == 'search'){
            //////////////////////////
            //  Editor Search Tool  //
            //////////////////////////
            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : 'everything';

            //Search everything
            if($data['subAction'] == 'everything'){

                //Get Articles
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
                        ->get();
                }else{
                    //Didn't find any, return empty array
                    $data['publicationResults'] = array();
                }
            //Search Aritcles
            }elseif($data['subAction'] == 'articles'){
                //Get Articles
                $data['articleResults'] = Article::where('instance_id', $instance->id)
                    ->where(function($query)
                    {
                        $query->Where('title','LIKE','%'.Input::get('search').'%')
                            ->orWhere('content','LIKE','%'.Input::get('search').'%');
                    })->get();
            //Search Publications
            }elseif($data['subAction'] == 'publications'){
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
            //Search Images
            }elseif($data['subAction'] == 'images'){
                //Grab all images for this instance
                $data['images'] = Image::where('instance_id',$instance->id);
            }

        } else
        {
            //Get most recent live publication
            $publication = Publication::has('articles')->where('instance_id', $instance->id)->
                where('published', 'Y')->
                orderBy('publish_date', 'desc')->first();

            //Populate $data
            $data['publication'] = $publication;

        }
        return View::make('editor', $data);
    }

    public function save()
    {
        //Grab Instance Name from URI
        $instanceID = urldecode(Request::segment(2));

        //Grab Action Type from URI
        $action = urldecode(Request::segment(3));

        $instance = Instance::find($instanceID);

        $default_tweakables = reindexArray(DefaultTweakable::all(), 'parameter', 'value');

        if ($action == 'settings')
        {
            foreach (Input::except('_token') as $parameter => $value)
            {
                //Check to see if this is a default value, if so don't duplicate things
                if ($default_tweakables[$parameter] == $value || $value == '')
                {
                    $tweakables = Tweakable::where('parameter', $parameter)->where('instance_id', $instanceID)->get();
                    foreach ($tweakables as $tweakable)
                    {
                        $tweakable->delete();
                    }

                } else
                {
                    $tweakable = Tweakable::firstOrCreate(array('parameter' => $parameter, 'instance_id' => $instanceID));
                    $tweakable->instance_id = $instanceID;
                    $tweakable->parameter = $parameter;
                    if ($parameter)
                        $tweakable->value = $value;
                    $tweakable->save();
                }
            }

            return Redirect::back()->withMessage('Successfully Saved Settings');


        }


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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.R
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}