<!DOCTYPE html>
<html>
<head>

    <title>The University of Akron Publication Editor</title>

    <link async rel="StyleSheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" type="text/css" />
    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>

    {{-- Pull in sub-templates for css and javascripts --}}
    @include('editor.editorStyle',array('default_tweakables' => $default_tweakables, 'tweakables' => $tweakables, 'default_tweakables_names' => $default_tweakables_names))

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
        <li @if($action == 'publications')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/publications')}}"><span class="glyphicon glyphicon-book"></span>&nbsp&nbspPublications</a></li>
        <li @if($action == 'images')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/images')}}"><span class="glyphicon glyphicon-picture"></span>&nbsp&nbspImages</a></li>
        <li @if($action == 'settings')class="active"@endif><a href="{{URL::to('edit/'.$instanceName.'/settings')}}"><span class="glyphicon glyphicon-wrench"></span>&nbsp&nbspSettings</a></li>
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
        <div class="col-sm-10 col-sm-offset-1 col-xs-12">

        {{-- Logic to pull in sub-templates --}}
        @if($action == 'articles')

            @include('editor.articleEditor',array('articles' => $articles))

        @elseif($action == 'publications')

            @include('editor.publicationEditor',array('publications' => $publications))

        @elseif($action == 'images')

            @include('editor.imageEditor')

        @elseif($action == 'settings')

            @include('editor.settingEditor',array('default_tweakables' => $default_tweakables,'tweakables' => $tweakables))

        @else

        {{-- Render currently live publications --}}
        <br/>
        <div class="col-sm-10 col-sm-offset-1 col-xs-12">
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
</div>
<div class="row" style="text-align:center">
    <a href="{{URL::to('/')}}  ">Publication List</a>
</div>
</body>
</html>