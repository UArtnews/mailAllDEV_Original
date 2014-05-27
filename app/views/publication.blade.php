<!DOCTYPE html>
<html>
<head>

    <title>{{$instance->name}} - {{date('m/d/Y', strtotime($publication->publish_date))}}</title>

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
</head>
<body>
<div class="row">
    <br/>
    <div class="col-xs-10 col-xs-offset-1">
        <div class="panel panel-default colorPanel">
            <div class="panel-heading" id="publicationPanelHead" style="text-align:center;">
                <h1>{{$instance->name}} - {{date('m/d/Y', strtotime($publication->publish_date))}}</h1>
            </div>
            <div class="panel-body" id="publicationPanelBody">
                <div class="row" id="publicationEditor{{$publication->id}}">
                    <div class="col-xs-10 col-xs-offset-1">
                        <h1 id="publicationTitle{{$publication->id}}"></h1>
                        <!-- Now to iterate through the articles -->
                        <div class="contentDiv">
                            <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
                            @foreach($publication->articles as $article)
                            <h1 id="articleTitle{{ $article->id }}" class="editable">{{$article->title}}</h1>
                            <p id="articleContent{{ $article->id }}" class="editable">{{$article->content}}<p>
                            <hr/>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="publicationPanelFoot">
            </div>
        </div>
    </div>
</body>
</html>