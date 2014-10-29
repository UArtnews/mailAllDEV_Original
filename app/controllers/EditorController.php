<?php

class EditorController extends \BaseController
{

    ////////////////////////
    //  edit/{instanceName}
    ///////////////////////
    public function index($subAction, $data){
        //Get most recent live publication
        $data['instance']->id;
        $publication = Publication::where('instance_id', $data['instance']->id)->
            where('published', 'Y')->
            where('type', 'regular')->
            orderBy('publish_date', 'desc')->
            with(array('articles' => function($query){
                $query->orderBy('order', 'asc');
            }))->
            first();

        //Populate $data
        $data['publication'] = $publication;

        return View::make('editor.editorDefault', $data);
    }


    ////////////////////////////////////////////////
    //  edit/{instanceName}/articles/{subAction}
    ////////////////////////////////////////////////
    public function articles($subAction, $data){
        $data['articles'] = Article::where('instance_id', $data['instance']->id)->orderBy('created_at', 'desc')->paginate(
            15
        );

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
        return View::make('editor.articleList', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/article/{subAction}
    ////////////////////////////////////////////////
    public function article($subAction, $data){
        $data['article'] = Article::find($subAction);

        return View::make('editor.articleEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/submissions/{subAction}
    ////////////////////////////////////////////////
    public function submissions($subAction, $data){

        $data['submissions'] = Submission::where('instance_id', $data['instance']->id)->orderBy(
            'created_at',
            'desc'
        )->paginate(15);
        
        return View::make('editor.submissionEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/publications/{subAction}
    ////////////////////////////////////////////////
    public function publications($subAction, $data){
        $data['publications'] = Publication::where('instance_id', $data['instance']->id)->orderBy(
            'publish_date',
            'desc'
        )->with(array('articles' => function($query){
                $query->orderBy('order', 'asc');
            }))->
        paginate(15);

        foreach ($data['publications'] as $publication) {
            $publication->submissions = Article::where('instance_id', $data['instance']->id)->where(
                'issue_dates',
                'LIKE',
                '%' . $publication->publish_date . '%'
            )->get();
        }

        //Get most recent live publication
        $data['currentLivePublication'] = Publication::where('instance_id', $data['instance']->id)->
        where('published', 'Y')->
        where('type', 'regular')->
        with(array('articles' => function($query){
            $query->orderBy('order', 'asc');
        }))->
        orderBy('publish_date', 'desc')->first();

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
                        'edit/' . $data['instance']->name . '/publication/' . $publication->id
                    ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>'
                );
            } else {
                $calPubs[$publication->publish_date . ' 10:00:00'][0] .= '<br/><div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                        'edit/' . $data['instance']->name . '/publication/' . $publication->id
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

        return View::make('editor.publicationList', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/publication/{subAction}
    ////////////////////////////////////////////////
    public function publication($subAction, $data){
        $data['publication'] = Publication::where('id', $subAction)->
        where('instance_id', $data['instance']->id)->
        with(array('articles' => function($query){
            $query->orderBy('order', 'asc');
        }))->first();

        //Package submissions
        $data['publication']->submissions = Article::where('instance_id', $data['instance']->id)->where(
            'issue_dates',
            'LIKE',
            '%' . $data['publication']->publish_date . '%'
        )->get();

        return View::make('editor.publicationEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/newPublication/{subAction}
    ////////////////////////////////////////////////
    public function newPublication($subAction, $data){
        if (Input::has('publish_date')) {
            $data['publish_date'] = date('m/d/Y', strtotime(urldecode(Input::get('publish_date'))));
        }

        return View::make('editor.newPublicationEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/images/{subAction}
    ////////////////////////////////////////////////
    public function images($subAction, $data){
        $data['images'] = Image::where('instance_id', $data['instance']->id)->orderBy('created_at', 'DESC')->get();

        return View::make('editor.imageEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/settings/{subAction}
    ////////////////////////////////////////////////
    public function settings($subAction, $data){

        if($subAction == null){
            $data['subAction'] = 'appearanceTweakables';
        }

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
            'publication-repeat-separator-toggle',
        );

        $data['headerFooterTweakables'] = array(
            'publication-web-header',
            'publication-header',
            'publication-footer',
            'publication-repeat-separator',
        );

        $data['workflowTweakables'] = array(
            'global-accepts-submissions'
        );

        $publication = Publication::where('instance_id', $data['instance']->id)->
            where('published', 'Y')->
            where('type', 'regular')->
            orderBy('publish_date', 'desc')->
            with(array('articles' => function($query){
                $query->orderBy('order', 'asc');
            }))->
            first();

        $data['publication'] = $publication;
        $data['isEditable'] = false;
        $data['isEmail'] = false;
        $data['shareIcons'] = false;

        return View::make('editor.settingEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/search/{subAction}
    ////////////////////////////////////////////////
    public function search($subAction, $data){
        //////////////////////////
        //  Editor Search Tool  //
        //////////////////////////

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

            //Get Images
            $data['imageResults'] = Image::where('instance_id', $data['instance']->id)
                ->where(function($query){
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('filename', 'LIKE', '%' . Input::get('search') . '%');
                    })->get();

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
                    ->select('publication.id',
                        'publication.published',
                        'publication.publish_date',
                        'publication.created_at',
                        'publication.updated_at',
                        'article.id as article_id',
                        'article.title')
                    ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                    ->join('article', 'publication_order.article_id', '=', 'article.id')
                    ->where('publication.instance_id', $data['instance']->id)
                    ->whereIn('publication_order.article_id', $articleArray)
                    ->groupBy('publication.id')
                    ->get();
            } else {
                //Didn't find any, return empty array
                $data['publicationResults'] = array();
            }
            //Search Articles
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
                    ->select('publication.id',
                        'publication.published',
                        'publication.publish_date',
                        'publication.created_at',
                        'publication.updated_at',
                        'article.id as article_id',
                        'article.title')
                    ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                    ->join('article', 'publication_order.article_id', '=', 'article.id')
                    ->where('publication.instance_id', $data['instance']->id)
                    ->whereIn('publication_order.article_id', $articleArray)
                    ->groupBy('publication.id')
                    ->get();
            } else {
                //Didn't find any, return empty array
                $data['publicationResults'] = array();
            }
        }elseif ($subAction == 'images'){
            $data['imageResults'] = Image::where('instance_id', $data['instance']->id)
                ->where(function($query){
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('filename', 'LIKE', '%' . Input::get('search') . '%');
                    })->get();
        }

        return View::make('editor.searchResults', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/help/{subAction}
    ////////////////////////////////////////////////
    public function help($subAction, $data){
        return View::make('editor.help', $data);
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