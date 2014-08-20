<!DOCTYPE html>
<html>
<head>

    <title>The University of Akron Publication Editor</title>

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css"/>
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>

    {{-- TODO: Conditionally load these js/css resources --}}
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}"/>

    {{-- Pull in sub-templates for css and javascripts --}}
    @include('editor.editorStyle')

    @include('editor.editorJavascript')


</head>
<body>
@include('editor.editorNav')
<div class="modal fade" id="cartModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <spanclass="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Article Cart <small>&nbsp;&nbsp;Articles ready for inclusion in a publication.</small>
                </h4>
            </div>
            <div class="modal-body">
                @if(isset($cart) && count($cart) > 0)
                <ul id="cartList" class="list-group">
                    @foreach($cart as $article_id => $title)
                    <li class="list-group-item cartItem">
                        <a href="{{ URL::to('edit/'.$instance->name.'/articles/'.$article_id) }}">{{ $title }}</a>
                        <button class="btn btn-xs btn-danger pull-right" onclick="removeArticleFromCart({{ $article_id }})">
                            Remove from cart
                        </button>
                    </li>
                    @endforeach
                </ul>
                @else
                <ul id="cartList" class="list-group">
                    <li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your
                        cart!
                    </li>
                </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="clearArticleCart()">Clear Cart</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-xs-12">

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
        <div class="panel panel-default colorPanel">
            <div class="panel-heading" id="articlePanelHead">Current Live Publication
                <span class="pull-right">
                    Published on {{date('m/d/Y',strtotime($publication->publish_date))}}
                    &nbsp;&nbsp;<a href="{{URL::to("/$instanceName/")}}"><span class="pull-right badge" style="background-color:red;">LIVE</span></a>
                </span>
            </div>
            <div class="panel-body" id="livePublicationBody">
                <div class="contentDiv" id="publication{{ $publication->id }}">
                    <img class="publicationBanner  img-responsive" src="{{$publication->banner_image}}"/>
                    {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
                    {{-- Insert Article Summary Conditionally --}}
                    @if( isset($tweakables['publication-headline-summary']) ?
                    $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                    <h3>Today's Headlines:</h3>
                    @foreach($publication->articles as $article)
                    <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                    @endforeach
                    @endif
                    <div class="article-container">
                        @foreach($publication->articles as $article)
                        @include('snippet.article', array('contentEditable' => true))
                        @endforeach
                    </div>
                    {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
                </div>
            </div>
            <div class="panel-footer" id="articlePanelFoot">
            </div>
        </div>
        @else
        <div class="panel panel-default colorPanel">
            <div class="panel-body">
                <div class="well">
                    <h2>No Publication to Display!</h2>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
<div class="row" style="text-align:center;">
    <a href="{{URL::to('/')}}  ">Publication List</a>
    &nbsp;|&nbsp;
    <a href="{{ URL::to($instance->name) }}">Live Publication View</a>
</div>
</body>
</html>