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
        } elseif ($action == 'publications')
        {
            $data['publications'] = Publication::where('instance_id', $instance->id)->orderBy('publish_date', 'desc')->paginate(15);
            $data['publications'][0]['articles'] = array();
            foreach ($data['publications'] as $id => $publication)
            {
                $data['publications'][$id]['articles'] = array();
                $articleArray = json_decode($publication->article_order);
                $articles = array();
                foreach ($articleArray as $articleID)
                {
                    array_push($articles, Article::find($articleID) );
                }
                $data['publications'][$id]['articles'] = $articles;
            }
        } elseif ($action == 'images')
        {

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

        } else
        {
            //Get most recent live publication
            $publication = Publication::where('instance_id', $instance->id)->
                where('published', 'Y')->
                orderBy('publish_date', 'desc')->first();

            $articles = array();

            //Get the article order array and grab the articles
            $articleArray = json_decode($publication->article_order);

            foreach ($articleArray as $articleID)
            {
                array_push($articles, Article::find($articleID));
            }

            //Populate $data
            $data['publication'] = $publication;
            $data['publication']->articles = $articles;

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

function reindexArray($array, $index, $value)
{

    $tempArr = array();

    foreach ($array as $item)
    {
        $tempArr[$item[$index]] = $item[$value];
    }

    return $tempArr;
}