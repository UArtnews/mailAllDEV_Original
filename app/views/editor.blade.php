<!DOCTYPE html>
<html>
<head>

    <title>The University of Akron Publication Editor</title>

    <link async rel="StyleSheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" type="text/css" />
    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>

    {{-- TODO:  Conditionally load these js/css resources --}}
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}" />

    {{-- Pull in sub-templates for css and javascripts --}}
    @include('editor.editorStyle')

    @include('editor.editorJavascript')


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
        <li @if($action == 'articles')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/articles')}}"><span class="glyphicon glyphicon-file"></span>&nbsp&nbspArticles</a></li>
        @if(isset($tweakables['global-accepts-submissions']) && $tweakables['global-accepts-submissions'] = 1)
        <li @if($action == 'submissions')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/submissions')}}"><span class="glyphicon glyphicon-inbox"></span>&nbsp&nbspSubmissions</a></li>
        @endif
        <li @if($action == 'publications')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/publications')}}"><span class="glyphicon glyphicon-book"></span>&nbsp&nbspPublications</a></li>
        <li @if($action == 'images')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/images')}}"><span class="glyphicon glyphicon-picture"></span>&nbsp&nbspImages</a></li>
        <li @if($action == 'settings')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/settings')}}"><span class="glyphicon glyphicon-wrench"></span>&nbsp&nbspSettings</a></li>
        <li @if($action == 'help')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/help')}}"><span class="glyphicon glyphicon-question-sign"></span>&nbsp&nbspHelp</a></li>
      </ul>
      <form id="searchForm" class="navbar-form navbar-right" role="search" action="{{ URL::to("edit/$instanceName/search/everything") }}" method="GET">
        <div class="form-group">
          <input type="text" name="search" class="form-control" placeholder="Search" size="8">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        @if(isset($cart))
        <li><a href="#" data-toggle="modal" data-target="#cartModal"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Article Cart&nbsp;<span id="cartCountBadge" class="badge" style="background-color:#428bca;">{{ count($cart) }}</span></a></li>
        @else
        <li><a href="#" data-toggle="modal" data-target="#cartModal"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Article Cart&nbsp;<span id="cartCountBadge" class="badge" style="background-color:#428bca;">0</span></a></li>
        @endif
        <li class="dropdown">
          <a href="#" id="SearchType" class="dropdown-toggle" data-toggle="dropdown">Search everything <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#" onclick="$('#SearchType').text('Search Articles');$('#searchForm').attr('action','{{ URL::to("edit/$instanceName/search/articles") }}');">Search Articles</a></li>
            <li><a href="#" onclick="$('#SearchType').text('Search Images');$('#searchForm').attr('action','{{ URL::to("edit/$instanceName/search/images") }}');">Search Images</a></li>
            <li><a href="#" onclick="$('#SearchType').text('Search Publications');$('#searchForm').attr('action','{{ URL::to("edit/$instanceName/search/publications") }}');">Search Publications</a></li>
            <li><a href="#" onclick="$('#SearchType').text('Search Everything');$('#searchForm').attr('action','{{ URL::to("edit/$instanceName/search/everything") }}');">Search Everything</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="modal fade" id="cartModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Article Cart<small>&nbsp;&nbsp;Articles ready for inclusion in a publication.</small></h4>
            </div>
            <div class="modal-body">
                @if(isset($cart) && count($cart) > 0)
                <ul id="cartList" class="list-group">
                    @foreach($cart as $article_id => $title)
                    <li class="list-group-item cartItem"><a href="{{ URL::to('edit/'.$instance->name.'/articles/'.$article_id) }}">{{ $title }}</a>&nbsp;&nbsp;<button class="btn btn-xs btn-danger" onclick="removeArticleFromCart({{ $article_id }})">Remove from cart</button></li>
                    @endforeach
                </ul>
                @else
                <ul id="cartList" class="list-group">
                    <li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your cart!</li>
                </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="clearArticleCart()">Clear Cart</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    <div class="row">
        <br/>
        <div class="col-sm-10 col-sm-offset-1 col-xs-12">

        {{-- Logic to pull in sub-templates --}}
        @if($action == 'articles')

            @include('editor.articleEditor')

        @elseif($action == 'submissions')

            @include('editor.submissionEditor')

        @elseif($action == 'publications')

            @include('editor.publicationEditor')

        @elseif($action == 'newPublication')

            @include('editor.newPublicationEditor')

        @elseif($action == 'images')

            @include('editor.imageEditor')

        @elseif($action == 'settings')

            @include('editor.settingEditor')

        @elseif($action == 'search')

            @include('editor.searchResults')

        @elseif($action == 'help')

            @include('editor.help')

        @else

            @if(isset($publication))

            {{-- Render currently live publications --}}
            <br/>
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="panel panel-default colorPanel">
                    <div class="panel-heading" id="articlePanelHead">Current Live Publication <span class="pull-right">Published on {{date('m/d/Y',strtotime($publication->publish_date))}}&nbsp&nbsp<a href="{{URL::to("/$instanceName/")}}"><span class="pull-right badge" style="background-color:red;">LIVE</span></a></span></div>
                    <div class="panel-body" id="livePublicationBody">
                        <div class="contentDiv" id="publication{{ $publication->id }}">
                            <img class="publicationBanner  img-responsive" src="{{$publication->banner_image}}"/>
                            {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
                            {{-- Insert Article Summary Conditionally --}}
                            @if( isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                                <h3>Today's Headlines:</h3>
                                @foreach($publication->articles as $article)
                                    <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                                @endforeach
                            @endif
                            @foreach($publication->articles as $article)
                                @include('snippet.article', array('contentEditable' => true))
                            @endforeach
                            {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
                        </div>
                    </div>
                    <div class="panel-footer" id="articlePanelFoot">
                    </div>
                </div>
            </div>
            @else
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="panel panel-default colorPanel">
                    <div class="panel-body">
                        <div class="well">
                            <h2>No Publication to Display!</h2>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
<div class="row" style="text-align:center;color:white;">
    <a href="{{URL::to('/')}}  ">Publication List</a> | <a href="{{ URL::to($instance->name) }}">Live Publication View</a>
</div>
</body>
</html>