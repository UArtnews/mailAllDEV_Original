<!DOCTYPE html>
<html>
<head>

    <title>The University of Akron Article Editor</title>

    <link async rel="StyleSheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <style>
        @if($serviceName == 'digest')
        body {
            background-color:#8AC8FF;
            color:#222;
        }
        h1 {
            text-shadow: 0 0 15px rgba(255,255,255,1);
        }

        @elseif($serviceName == 'zipmail')
        body {
            background-color:#00285e;
            color:#fff;
        }
        h1 {
            text-shadow: 0 0 15px rgba(0,0,0,1);
        }
        @else
        body {
            background-color:#B56D6D;
            color:#fff;
        }
        h1 {
            text-shadow: 0 0 15px rgba(0,0,0,1);
        }
        @endif


        input {
            color:#222;
            box-shadow:0 0 30px rgba(0,0,0,0.3);
            border:0px;
            padding:1px;
            font-weight:700;
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

    </style>

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
      <a class="navbar-brand" href="#">{{ucfirst($serviceName)}}</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li @if($action == 'articles')class="active"@endif><a href="{{URL::to('edit/'.$serviceName.'/articles')}}">Articles</a></li>
        <li @if($action == 'publications')class="active"@endif><a href="{{URL::to('edit/'.$serviceName.'/publications')}}">Publications</a></li>
        <li @if($action == 'images')class="active"@endif><a href="{{URL::to('edit/'.$serviceName.'/images')}}">Images</a></li>
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
            <div class="panel panel-default">
                <div class="panel-heading" id="articlePanelHead">Choose an Article</div>
                <div class="panel-body" id="articlePanelBody">
                    <div class="col-xs-10 col-xs-offset-1" id="articleChooser">
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 1');">{{ucfirst($serviceName)}} Article 1  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 2');">{{ucfirst($serviceName)}} Article 2  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 3');">{{ucfirst($serviceName)}} Article 3  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 4');">{{ucfirst($serviceName)}} Article 4  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 5');">{{ucfirst($serviceName)}} Article 5  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 6');">{{ucfirst($serviceName)}} Article 6  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#articleEditor').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle').text('Now Editing {{ucfirst($serviceName)}} Article 7');">{{ucfirst($serviceName)}} Article 7  -  Created on 5/12/2015  by  Anon</a></li>
                        </ul>
                    </div>
                    <div class="row" id="articleEditor" style="display:none;">
                        <div class="col-xs-10 col-xs-offset-1">
                            <h3 id="articleTitle"></h3>
                            <p>
                                Here is some text that you will be able to edit, just pretend for now.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" id="articlePanelFoot">
                </div>
            </div>
        @elseif($action == 'publications')
            <div class="panel panel-default">
                <div class="panel-heading" id="publicationPanelHead">Choose an publication</div>
                <div class="panel-body" id="publicationPanelBody">
                    <div class="col-xs-10 col-xs-offset-1" id="publicationChooser">
                        <ul class="list-unstyled">
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 1');">{{ucfirst($serviceName)}} Publication 1  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 2');">{{ucfirst($serviceName)}} Publication 2  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 3');">{{ucfirst($serviceName)}} Publication 3  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 4');">{{ucfirst($serviceName)}} Publication 4  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 5');">{{ucfirst($serviceName)}} Publication 5  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 6');">{{ucfirst($serviceName)}} Publication 6  -  Created on 5/12/2015  by  Anon</a></li>
                            <li><a href="#" onclick="$('#publicationEditor').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle').text('Now Viewing {{ucfirst($serviceName)}} Publication 7');">{{ucfirst($serviceName)}} Publication 7  -  Created on 5/12/2015  by  Anon</a></li>
                        </ul>
                    </div>
                    <div class="row" id="publicationEditor" style="display:none;">
                        <div class="col-xs-10 col-xs-offset-1">
                            <h3 id="publicationTitle"></h3>
                            <p>
                                Here's a nice preview of the editor.</br>

                                You'll be able to drag and drop this stuff to rearrange an article soon!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" id="publicationPanelFoot">
                </div>
            </div>

        @endif
        </div>
    </div>
</body>
</html>