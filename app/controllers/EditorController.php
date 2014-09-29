<?php

class EditorController extends \BaseController
{

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

        if (Session::has('cart')) {
            $cart = Session::get('cart');

            if (isset($cart[$instance->id])) {
                $data['cart'] = $cart[$instance->id];
            }
        }

        if (isset($data['tweakables']['global-accepts-submissions'])) {
            if ($data['tweakables']['global-accepts-submissions']) {
                $data['submission'] = true;
            } else {
                $data['submission'] = false;
            }
        } else {
            if ($data['default_tweakables']['global-accepts-submissions']) {
                $data['submission'] = true;
            } else {
                $data['submission'] = false;
            }
        }

        if ($action == 'articles') {
            $data['articles'] = Article::where('instance_id', $instance->id)->orderBy('created_at', 'desc')->paginate(
                15
            );

            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';

            if ($data['subAction'] != '') {
                $data['directArticle'] = Article::findOrFail($data['subAction']);
            }

            //Check if this article will be loaded and can be shortcut (simplifies save process)
            $data['directIsLoaded'] = false;
            foreach ($data['articles'] as $article) {
                if ($article->id == $data['subAction']) {
                    $data['directIsLoaded'] = true;
                }
            }

        } elseif ($action == 'submissions') {
            $data['submissions'] = Submission::where('instance_id', $instance->id)->orderBy(
                'created_at',
                'desc'
            )->paginate(15);

        } elseif ($action == 'publications') {
            //return var_dump(Article::where('instance_id', 1)->where('issue_dates','LIKE','%2014-07-16%')->count()).'<br/>'.var_dump(DB::getQueryLog());

            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';

            $data['publications'] = Publication::where('instance_id', $instance->id)->orderBy(
                'publish_date',
                'desc'
            )->with('articles')->paginate(15);

            foreach ($data['publications'] as $publication) {
                $publication->submissions = Article::where('instance_id', $instance->id)->where(
                    'issue_dates',
                    'LIKE',
                    '%' . $publication->publish_date . '%'
                )->get();
            }

            //Get most recent live publication
            $data['currentLivePublication'] = Publication::with('articles')->where('instance_id', $instance->id)->
            where('published', 'Y')->
            orderBy('publish_date', 'desc')->first();


            if ($data['subAction'] != '') {
                $data['directPublication'] = Publication::with('articles')->find($data['subAction']);

                if (count($data['directPublication']) == 0) {
                    $data['directPublication'] = Publication::find($data['subAction']);
                }

                $data['directPublication']->submissions = Article::where('instance_id', $instance->id)->where(
                    'issue_dates',
                    'LIKE',
                    '%' . $data['directPublication']->publish_date . '%'
                )->get();

                $data['directPublication']->id = $data['subAction'];

                $data['directIsLoaded'] = false;

                //Check if this publication will be loaded and can be shortcut
                foreach ($data['publications'] as $publication) {
                    if ($publication->id == $data['subAction']) {
                        $data['directIsLoaded'] = true;
                    }
                }
            }

            $calPubs = array();
            foreach (Publication::where('instance_id', $instance->id)->get() as $publication) {
                $button = '';

                if (isset($data['currentLivePublication']) && $publication->id == $data['currentLivePublication']->id) {
                    $button = '<a href="' . URL::to($instance->name) . '" class="btn btn-xs btn-danger" >Live</a>';
                } elseif ($publication->published == 'Y') {
                    $button = '<button class="btn btn-xs btn-success" disabled="disabled">Published</button>';
                } else {
                    $button = '<button class="btn btn-xs btn-default" disabled="disabled">Unpublished</button>';
                }
                if (!array_key_exists($publication->publish_date . ' 10:00:00', $calPubs)) {
                    $calPubs[$publication->publish_date . ' 10:00:00'] = array(
                        '<div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                            'edit/' . $instance->name . '/publications/' . $publication->id
                        ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>'
                    );
                } else {
                    $calPubs[$publication->publish_date . ' 10:00:00'][0] .= '<br/><div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                            'edit/' . $instance->name . '/publications/' . $publication->id
                        ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>';
                }
            }

            //Organize Calendar
            $cal = Calendar::make();
            $cal->setBasePath(URL::to('edit/' . $instance->name . '/publications'));
            $cal->setDate(Input::get('cdate'));
            $cal->setView(Input::get('cv')); //'day' or 'week' or null
            $cal->setStartEndHours(8, 20); // Set the hour range for day and week view
            $cal->setTimeClass('ctime'); //Class Name for times column on day and week views
            $cal->setEventsWrap(array('<p>', '</p>')); // Set the event's content wrapper
            $cal->setDayWrap(
                array(
                    '<div class="btn-group" style="padding-bottom:.25em;"><button class="btn btn-default btn-disabled" disabled="disabled">',
                    '</button><button class="btn btn btn-success" style="padding-left:2px!important;padding-right:2px!important;" onclick="newPublicationFromCal(this)">&nbsp;+&nbsp;</button></div>'
                )
            ); //Set the day's number wrapper
            $cal->setNextIcon(
                '<button class="btn btn-default">&gt;&gt;</button>'
            ); //Can also be html: <i class='fa fa-chevron-right'></i>
            $cal->setPrevIcon('<button class="btn btn-default">&lt;&lt;</button>'); // Same as above
            $cal->setDayLabels(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')); //Label names for week days
            $cal->setMonthLabels(
                array(
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                )
            ); //Month names
            $cal->setDateWrap(array('<div class="calendarDay">', '</div>')); //Set cell inner content wrapper
            $cal->setTableClass('table table-bordered calendarTable'); //Set the table's class name
            $cal->setHeadClass('table-header'); //Set top header's class name
            $cal->setNextClass(''); // Set next btn class name
            $cal->setPrevClass(''); // Set Prev btn class name
            $cal->setEvents($calPubs);
            $data['calendar'] = $cal->generate();

        } elseif ($action == 'newPublication') {
            if (Input::has('publish_date')) {
                $data['publish_date'] = date('m/d/Y', strtotime(urldecode(Input::get('publish_date'))));
            }

        } elseif ($action == 'images') {
            //Do image listing and upload
            $data['images'] = Image::where('instance_id', $instance->id)->orderBy('created_at', 'DESC')->get();


        } elseif ($action == 'settings') {
            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(
                Request::segment(4)
            ) : 'appearanceTweakables';

            $data['appearanceTweakables'] = array(
                'global-background-color',
                'publication-background-color',
                'publication-border-color',
                'publication-h1-color',
                'publication-h1-font',
                'publication-h1-font-size',
                'publication-h1-font-weight',
                'publication-h1-line-height',
                'publication-h2-color',
                'publication-h2-font',
                'publication-h2-font-size',
                'publication-h2-font-weight',
                'publication-h2-line-height',
                'publication-h3-color',
                'publication-h3-font',
                'publication-h3-font-size',
                'publication-h3-font-weight',
                'publication-h3-line-height',
                'publication-h4-color',
                'publication-h4-font',
                'publication-h4-font-size',
                'publication-h4-font-weight',
                'publication-h4-line-height',
                'publication-p-color',
                'publication-p-font',
                'publication-p-font-size',
                'publication-p-font-weight',
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

            $data['workflowTweakables'] = array(
                'global-accepts-submissions'
            );

        } elseif ($action == 'search') {
            //////////////////////////
            //  Editor Search Tool  //
            //////////////////////////
            $data['subAction'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : 'everything';

            //Search everything
            if ($data['subAction'] == 'everything') {

                //Get Articles
                $data['articleResults'] = Article::where('instance_id', $instance->id)
                    ->where(
                        function ($query) {
                            $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                                ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                        }
                    )->get();

                //If we returned articles, go find their publications
                if (count($data['articleResults']) > 0) {
                    //Create array of article ID's for looking up publications
                    $articleArray = array();
                    foreach ($data['articleResults'] as $articleResult) {
                        //make an array of all article id's
                        array_push($articleArray, $articleResult->id);
                    }

                    //Get Publications where Articles Appear
                    $data['publicationResults'] = DB::table('publication')
                        ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                        ->where('publication.instance_id', $instance->id)
                        ->whereIn('publication_order.article_id', $articleArray)
                        ->groupBy('publication.id')
                        ->get();
                } else {
                    //Didn't find any, return empty array
                    $data['publicationResults'] = array();
                }
                //Search Aritcles
            } elseif ($data['subAction'] == 'articles') {
                //Get Articles
                $data['articleResults'] = Article::where('instance_id', $instance->id)
                    ->where(
                        function ($query) {
                            $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                                ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                        }
                    )->get();
                //Search Publications
            } elseif ($data['subAction'] == 'publications') {
                //Get Articles which we'll find the pubs with
                $data['articleResults'] = Article::where('instance_id', $instance->id)
                    ->where(
                        function ($query) {
                            $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                                ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                        }
                    )->get();

                //If we returned articles, go find their publications
                if (count($data['articleResults']) > 0) {
                    //Create array of article ID's for looking up publications
                    $articleArray = array();
                    foreach ($data['articleResults'] as $articleResult) {
                        //make an array of all article id's
                        array_push($articleArray, $articleResult->id);
                    }

                    //Get Publications where Articles Appear
                    $data['publicationResults'] = DB::table('publication')
                        ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                        ->where('publication.instance_id', $instance->id)
                        ->whereIn('publication_order.article_id', $articleArray)
                        ->groupBy('publication.id')
                        ->get();
                } else {
                    //Didn't find any, return empty array
                    $data['publicationResults'] = array();
                }
                //Search Images
            } elseif ($data['subAction'] == 'images') {
                //Grab all images for this instance
                $data['images'] = Image::where('instance_id', $instance->id);
            }

        } elseif ($action == 'help') {
            //Display Help Stuff
        } else {
            //Get most recent live publication
            $publication = Publication::with('articles')->where('instance_id', $instance->id)->
            where('published', 'Y')->
            orderBy('publish_date', 'desc')->first();

            //Populate $data
            $data['publication'] = $publication;

        }
        return View::make('editor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/articles/{subAction}
    ////////////////////////////////////////////////
    public function articles($subAction, $data){
        $data['articles'] = Article::where('instance_id', $data['instance']->id)->orderBy('created_at', 'desc')->paginate(
            15
        );

        $subAction = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';

        if ($subAction != '') {
            $data['directArticle'] = Article::findOrFail($subAction);
        }

        //Check if this article will be loaded and can be shortcut (simplifies save process)
        $data['directIsLoaded'] = false;
        foreach ($data['articles'] as $article) {
            if ($article->id == $subAction) {
                $data['directIsLoaded'] = true;
            }
        }
        return View::make('editor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/submissions/{subAction}
    ////////////////////////////////////////////////
    public function submissions($subAction, $data){
        $data['submissions'] = Submission::where('instance_id', $data['instance']->id)->orderBy(
            'created_at',
            'desc'
        )->paginate(15);
        
        return View::make('editor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/publications/{subAction}
    ////////////////////////////////////////////////
    public function publications($subAction, $data){
        $subAction = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';

        $data['publications'] = Publication::where('instance_id', $data['instance']->id)->orderBy(
            'publish_date',
            'desc'
        )->with('articles')->paginate(15);

        foreach ($data['publications'] as $publication) {
            $publication->submissions = Article::where('instance_id', $data['instance']->id)->where(
                'issue_dates',
                'LIKE',
                '%' . $publication->publish_date . '%'
            )->get();
        }

        //Get most recent live publication
        $data['currentLivePublication'] = Publication::with('articles')->where('instance_id', $data['instance']->id)->
        where('published', 'Y')->
        orderBy('publish_date', 'desc')->first();


        if ($subAction != '') {
            $data['directPublication'] = Publication::with('articles')->find($subAction);

            if (count($data['directPublication']) == 0) {
                $data['directPublication'] = Publication::find($subAction);
            }

            $data['directPublication']->submissions = Article::where('instance_id', $data['instance']->id)->where(
                'issue_dates',
                'LIKE',
                '%' . $data['directPublication']->publish_date . '%'
            )->get();

            $data['directPublication']->id = $subAction;

            $data['directIsLoaded'] = false;

            //Check if this publication will be loaded and can be shortcut
            foreach ($data['publications'] as $publication) {
                if ($publication->id == $subAction) {
                    $data['directIsLoaded'] = true;
                }
            }
        }

        $calPubs = array();
        foreach (Publication::where('instance_id', $data['instance']->id)->get() as $publication) {
            $button = '';

            if (isset($data['currentLivePublication']) && $publication->id == $data['currentLivePublication']->id) {
                $button = '<a href="' . URL::to($data['instance']->name) . '" class="btn btn-xs btn-danger" >Live</a>';
            } elseif ($publication->published == 'Y') {
                $button = '<button class="btn btn-xs btn-success" disabled="disabled">Published</button>';
            } else {
                $button = '<button class="btn btn-xs btn-default" disabled="disabled">Unpublished</button>';
            }
            if (!array_key_exists($publication->publish_date . ' 10:00:00', $calPubs)) {
                $calPubs[$publication->publish_date . ' 10:00:00'] = array(
                    '<div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                        'edit/' . $data['instance']->name . '/publications/' . $publication->id
                    ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>'
                );
            } else {
                $calPubs[$publication->publish_date . ' 10:00:00'][0] .= '<br/><div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                        'edit/' . $data['instance']->name . '/publications/' . $publication->id
                    ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>';
            }
        }

        //Organize Calendar
        $cal = Calendar::make();
        $cal->setBasePath(URL::to('edit/' . $data['instance']->name . '/publications'));
        $cal->setDate(Input::get('cdate'));
        $cal->setView(Input::get('cv')); //'day' or 'week' or null
        $cal->setStartEndHours(8, 20); // Set the hour range for day and week view
        $cal->setTimeClass('ctime'); //Class Name for times column on day and week views
        $cal->setEventsWrap(array('<p>', '</p>')); // Set the event's content wrapper
        $cal->setDayWrap(
            array(
                '<div class="btn-group" style="padding-bottom:.25em;"><button class="btn btn-default btn-disabled" disabled="disabled">',
                '</button><button class="btn btn btn-success" style="padding-left:2px!important;padding-right:2px!important;" onclick="newPublicationFromCal(this)">&nbsp;+&nbsp;</button></div>'
            )
        ); //Set the day's number wrapper
        $cal->setNextIcon(
            '<button class="btn btn-default">&gt;&gt;</button>'
        ); //Can also be html: <i class='fa fa-chevron-right'></i>
        $cal->setPrevIcon('<button class="btn btn-default">&lt;&lt;</button>'); // Same as above
        $cal->setDayLabels(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')); //Label names for week days
        $cal->setMonthLabels(
            array(
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            )
        ); //Month names
        $cal->setDateWrap(array('<div class="calendarDay">', '</div>')); //Set cell inner content wrapper
        $cal->setTableClass('table table-bordered calendarTable'); //Set the table's class name
        $cal->setHeadClass('table-header'); //Set top header's class name
        $cal->setNextClass(''); // Set next btn class name
        $cal->setPrevClass(''); // Set Prev btn class name
        $cal->setEvents($calPubs);
        $data['calendar'] = $cal->generate();

        return View::make('editor', $data);
    }

    public function newPublication($subAction, $data){
        if (Input::has('publish_date')) {
            $data['publish_date'] = date('m/d/Y', strtotime(urldecode(Input::get('publish_date'))));
        }

        return View::make('editor', $data);
    }

    public function images($subAction, $data){
        $data['images'] = Image::where('instance_id', $data['instance']->id)->orderBy('created_at', 'DESC')->get();

        return View::make('editor', $data);
    }

    public function settings($subAction, $data){
        $subAction = urldecode(Request::segment(4)) ? urldecode(
            Request::segment(4)
        ) : 'appearanceTweakables';

        $data['appearanceTweakables'] = array(
            'global-background-color',
            'publication-background-color',
            'publication-border-color',
            'publication-h1-color',
            'publication-h1-font',
            'publication-h1-font-size',
            'publication-h1-font-weight',
            'publication-h1-line-height',
            'publication-h2-color',
            'publication-h2-font',
            'publication-h2-font-size',
            'publication-h2-font-weight',
            'publication-h2-line-height',
            'publication-h3-color',
            'publication-h3-font',
            'publication-h3-font-size',
            'publication-h3-font-weight',
            'publication-h3-line-height',
            'publication-h4-color',
            'publication-h4-font',
            'publication-h4-font-size',
            'publication-h4-font-weight',
            'publication-h4-line-height',
            'publication-p-color',
            'publication-p-font',
            'publication-p-font-size',
            'publication-p-font-weight',
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

        $data['workflowTweakables'] = array(
            'global-accepts-submissions'
        );
        
        return View::make('editor', $data);
    }

    public function search($subAction, $data){
        //////////////////////////
        //  Editor Search Tool  //
        //////////////////////////
        $subAction = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : 'everything';

        //Search everything
        if ($subAction == 'everything') {

            //Get Articles
            $data['articleResults'] = Article::where('instance_id', $data['instance']->id)
                ->where(
                    function ($query) {
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                    }
                )->get();

            //If we returned articles, go find their publications
            if (count($data['articleResults']) > 0) {
                //Create array of article ID's for looking up publications
                $articleArray = array();
                foreach ($data['articleResults'] as $articleResult) {
                    //make an array of all article id's
                    array_push($articleArray, $articleResult->id);
                }

                //Get Publications where Articles Appear
                $data['publicationResults'] = DB::table('publication')
                    ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                    ->where('publication.instance_id', $data['instance']->id)
                    ->whereIn('publication_order.article_id', $articleArray)
                    ->groupBy('publication.id')
                    ->get();
            } else {
                //Didn't find any, return empty array
                $data['publicationResults'] = array();
            }
            //Search Aritcles
        } elseif ($subAction == 'articles') {
            //Get Articles
            $data['articleResults'] = Article::where('instance_id', $data['instance']->id)
                ->where(
                    function ($query) {
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                    }
                )->get();
            //Search Publications
        } elseif ($subAction == 'publications') {
            //Get Articles which we'll find the pubs with
            $data['articleResults'] = Article::where('instance_id', $data['instance']->id)
                ->where(
                    function ($query) {
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                    }
                )->get();

            //If we returned articles, go find their publications
            if (count($data['articleResults']) > 0) {
                //Create array of article ID's for looking up publications
                $articleArray = array();
                foreach ($data['articleResults'] as $articleResult) {
                    //make an array of all article id's
                    array_push($articleArray, $articleResult->id);
                }

                //Get Publications where Articles Appear
                $data['publicationResults'] = DB::table('publication')
                    ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                    ->where('publication.instance_id', $data['instance']->id)
                    ->whereIn('publication_order.article_id', $articleArray)
                    ->groupBy('publication.id')
                    ->get();
            } else {
                //Didn't find any, return empty array
                $data['publicationResults'] = array();
            }
            //Search Images
        } elseif ($subAction == 'images') {
            //Grab all images for this instance
            $data['images'] = Image::where('instance_id', $data['instance']->id);
        }

        return View::make('editor', $data);
    }

    public function help($subAction, $data){
        return View::make('editor', $data);
    }
    
    public function NEWindex($subAction, $data){
        //Get most recent live publication
        $publication = Publication::with('articles')->where('instance_id', $data['instance']->id)->
        where('published', 'Y')->
        orderBy('publish_date', 'desc')->first();

        //Populate $data
        $data['publication'] = $publication;

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

        if ($action == 'settings') {
            foreach (Input::except('_token') as $parameter => $value) {
                //Check to see if this is a default value, if so don't duplicate things
                if ($default_tweakables[$parameter] == $value || trim(strip_tags($value)) == '') {
                    $tweakables = Tweakable::where('parameter', $parameter)->where('instance_id', $instanceID)->get();
                    foreach ($tweakables as $tweakable) {
                        $tweakable->delete();
                    }

                } else {
                    $tweakable = Tweakable::firstOrCreate(
                        array('parameter' => $parameter, 'instance_id' => $instanceID)
                    );
                    $tweakable->instance_id = $instanceID;
                    $tweakable->parameter = $parameter;
                    if ($parameter) {
                        $tweakable->value = stripslashes($value);
                    }
                    $tweakable->save();
                }
            }

            return Redirect::back()->withMessage('Successfully Saved Settings');


        }


    }


}