<!DOCTYPE html>
<html>
<head>

    <title>The University of Akron Publication Editor</title>

    <link async rel="StyleSheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>

    <style>
        body {
            background-color:#222;
            color:#222;
        }

        p {
        	font-size:{{$tweakables['global-font-size']['value'] or '1em'}};
        }

        h1 {
        	color:{{$tweakables['global-h2-color']['value'] or '#222'}};
        }

        input {
            color:#222;
            box-shadow:0 0 30px rgba(0,0,0,0.3);
            border:0px;
            padding:1px;
            font-weight:700;
        }

        .colorPanel {
            color:#222;
            background-color:{{$tweakables['publication-background-color']['value'] or '#222'}};
        }

        .error {
            color:red;
            font-weight:bold;
        }

        #logo {
            margin-bottom:1.5em;
        }

        .centerMe {
            margin:0em auto;
            text-align:center;
        }


        .digestLogo {
            margin-bottom:1.5em;
            -webkit-border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding:5px;

            border-color: #B2E6FF;
            border-width: 5px;
            border-style: solid;
            width:210px;
            overflow:hidden;
        }

        .zipmailLogo {
            margin-bottom:1.5em;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding:5px;

            border-color: #00285E;
            border-width: 5px;
            border-style: solid;
            width:210px;
            overflow:hidden;
        }

        .waynemailLogo {
            margin-bottom:1.5em;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding:5px;

            border-color: #910F0F;
            border-width: 5px;
            border-style: solid;
            width:210px;
            overflow:hidden;
        }

        .logo:hover {
            box-shadow: 0 0 30px rgba(255,255,255,.6);
        }

        .contentDiv{
            width:510px;
            padding:5px;
            margin:0em auto;
            position:relative;
            background-color:{{$tweakables['publication-border-color']['value'] or '#eee'}};

        }

        .publicationBanner {
            margin-bottom:1em;
        }

        .editorSaveRevert {
            background-color:rgba(255,255,255,0.5);
            padding:5px;
            position:absolute;
            width:100px;
        }

        .editorIndicator {
            background-color:rgba(255,0,0,0.75);
            position:absolute;
            width:5px;

        }

    </style>

    <script>
        //EditorContents variable for storing/retrieving original copies of articles
        var EditorData = (function(){
            var editorContents = [];

            return {
                contents: editorContents,
            };
        })();

        function revertEdits(id)
        {
            $('#'+id).html(EditorData.contents[id]);
        }

        function saveEdits(id)
        {
            EditorData.contents[id] = $('#'+id).html();
            $('#'+id+'indicator').css('background-color','rgba(0,255,0,0.75)');
            setTimeout(function(){
                $('#'+id+'indicator').remove();
            },2000);

        }


        $(document).ready(function(){

            //Prepare click handler for all editable elements
            $('.editable').click(function() {
                //Save the content as it currently is into the
                if(typeof EditorData.contents[this.id] == 'undefined'){
                    EditorData.contents[this.id] = $(this).html();
                }

                //Remove all other editorSaveRevert divs
                $('.editorSaveRevert').remove();

                //Place save/revert controls off to side of article
                $controls = '<div id="'+this.id+'save" class="editorSaveRevert" ><button type="button" class="btn btn-primary btn-block" onclick="saveEdits(\''+this.id+'\');">Save</button><button type="button" class="btn btn-warning btn-block" onclick="revertEdits(\''+this.id+'\');">Revert</button></div>';
                $(this).after($controls);

                //Adjust positioning of save/revert controls
                $('#'+this.id+'save').css('top',$(this).position().top+'px');
                $('#'+this.id+'save').css('left',$(this).parent().outerWidth()+'px');

                //Check if instance is already fired up.  Exit click handler if already fired up, we're done here.
                var name;
                for(name in CKEDITOR.instances) {
                    var instance = CKEDITOR.instances[name];
                    if(this && this == instance.element.$) {
                        return;
                    }
                }

                //Init editor since it's not fired up!
                $(this).attr('contenteditable', true);
                CKEDITOR.inline(this);
                $(this).trigger('click');


            });
        });
    </script>

</head>
<body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{URL::to('edit/'.$instanceName)}}">{{ucfirst($instanceName)}}</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li @if($action == 'articles')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/articles')}}">Articles</a></li>
        <li @if($action == 'publications')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/publications')}}">Publications</a></li>
        <li @if($action == 'images')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/images')}}">Images</a></li>
      </ul>
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" size="8">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" id="SearchType" class="dropdown-toggle" data-toggle="dropdown">Search everything <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#" onclick="$('#SearchType').text('Search Articles')">Search Articles</a></li>
            <li><a href="#" onclick="$('#SearchType').text('Search Images')">Search Images</a></li>
            <li><a href="#" onclick="$('#SearchType').text('Search Publications')">Search Publications</a></li>
            <li><a href="#" onclick="$('#SearchType').text('Search Everything')">Search Everything</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
    <div class="row">
        <br/>
        <div class="col-xs-10 col-xs-offset-1">
        @if($action == 'articles')
            <div class="panel panel-default colorPanel">
                <div class="panel-heading" id="articlePanelHead">Article Editor</div>
                <div class="panel-body" id="articlePanelBody">
                    <div class="col-xs-10 col-xs-offset-1" id="articleChooser">
                        <ul class="list-unstyled">
                            @foreach($articles as $article)
                                <li>

                                <a href="#" onclick="$('#articleEditor{{$article->id}}').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle{{$article->id}}').text('{{$article->title}}');">
                                {{$article->title}}  -  Created on {{date('m/d/Y', strtotime($article->created_at))}}  by  {{User::find($article->author_id)->first}} {{User::find($article->author_id)->last}}
                                </a>

                                </li>
                                <br/>
                            @endforeach
                        </ul>
                    </div>
                    @foreach($articles as $article)
                    <div class="row" id="articleEditor{{$article->id}}" style="display:none;">
                        <div class="col-xs-10 col-xs-offset-1 article">
                            <div class="contentDiv">
                                <h3 id="articleTitle{{$article->id}}" class="editable"></h3>
                                <p id="articleContent{{ $article->id }}" class="editable">
                                {{$article->content}}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer" id="articlePanelFoot">
                </div>
            </div>
        @elseif($action == 'publications')
            <div class="panel panel-default colorPanel">
                <div class="panel-heading" id="publicationPanelHead">Publication Editor</div>
                <div class="panel-body" id="publicationPanelBody">
                    <div class="col-xs-10 col-xs-offset-1" id="publicationChooser">
                        <ul class="list-unstyled">
                            @foreach($publications as $publication)
                                <li>

                                <a href="#" onclick="$('#publicationEditor{{$publication->id}}').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle{{$publication->id}}').text('Now Viewing {{$instanceName}} - {{date('m/d/Y', strtotime($publication->created_at))}}');">
                                    {{ ucfirst($instanceName) }} - Created on {{date('m/d/Y', strtotime($publication->created_at))}}
                                </a>

                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @foreach($publications as $publication)
                    <div class="row" id="publicationEditor{{$publication->id}}" style="display:none;">
                        <div class="col-xs-10 col-xs-offset-1">
                            <h1 id="publicationTitle{{$publication->id}}"></h1>
                            <!-- Now to iterate through the articles -->
                            <div class="contentDiv">
                                <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
                                @foreach($publications->articles as $article)
                                    <h1 id="articleTitle{{ $article->id }}" class="editable">{{$article->title}}</h1>
                                    <p id="articleContent{{ $article->id }}" class="editable">{{$article->content}}<p>
                                    <hr/>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer" id="publicationPanelFoot">
                </div>
            </div>

        @elseif($action == 'images')
        @else
        <!-- Render the currently-live publication -->
        <br/>
        <div class="col-xs-10 col-xs-offset-1">
            <div class="panel panel-default colorPanel">
                <div class="panel-heading" id="articlePanelHead">Current Live Publication <span class="pull-right">Published on {{date('m/d/Y',strtotime($publication->publish_date))}}&nbsp&nbsp<a href="{{URL::to("/$instanceName/")}}"><span class="pull-right badge" style="background-color:red;">LIVE</span></a></span></div>
                <div class="panel-body" id="livePublicationBody">
                    <div class="contentDiv">
                        <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
                        @foreach($publication->articles as $article)
                            <h1 id="articleTitle{{ $article->id }}" class="editable">{{$article->title}}</h1>
                            <p id="articleContent{{ $article->id }}" class="editable">{{$article->content}}<p>
                            <hr/>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer" id="articlePanelFoot">
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>