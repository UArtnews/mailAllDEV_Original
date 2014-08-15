<!DOCTYPE html>
<html>
<head>

    <title>{{$instance->name}} - Archives</title>

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    @include('editor.editorStyle',array('default_tweakables' => $default_tweakables, 'tweakables' => $tweakables, 'default_tweakables_names' => $default_tweakables_names))

</head>
<body>
@include('publication.publicNav', array('instanceName' => $instanceName))
<div class="row">
    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="panel panel-default colorPanel">
            <div class="panel-heading" id="publicationPanelHeading">Archives
                <button class="btn btn-xs btn-primary pull-right" onclick="$('.contentDiv').slideUp();$('.chooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp;Back to Archive Listing</button>
            </div>
            <div class="panel-body" id="publicationPanelBody">
                <table class="table well chooser">
                    <thead>
                    <tr>
                        <th>Publish Date</th>
                        <th>Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($publications as $publication)
                    <tr>
                        <td>
                            <a href="#" onclick="$('#publication{{ $publication->id }}').slideToggle();$('.chooser').slideToggle();">
                                {{ date('m/d/Y', strtotime($publication->publish_date)) }}
                            </a>
                        </td>
                        <td>{{ucfirst($publication->type)}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3">
                            {{$publications->links()}}
                        </th>
                    </tr>
                    </tfoot>
                </table>

                @foreach($publications as $publication)
                    @include('publication.staticWebPublication', array('hide' => true))
                @endforeach

                @if(isset($publication_id))
                    @if(!$directIsLoaded)
                        @include('publication.staticWebPublication', array('publication' => $directPublication))
                        <script>
                            $(document).ready(function(){
                                $('.chooser').slideToggle();
                            });
                        </script>
                    @else
                        <script>
                            $(document).ready(function(){
                                $('.chooser').slideToggle();
                                $('#publication{{ $publication_id }}').slideToggle();
                            });
                        </script>
                    @endif
                @else
                @endif

            </div>
            <div class="panel-footer" id="publicationPanelFoot">
                Published on {{ $publication->publish_date }} |  <a href="{{ URL::to($instance->name.'/archive') }}">Archive</a>
            </div>
        </div>
    </div>
</body>
</html>