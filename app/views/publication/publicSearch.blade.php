<!DOCTYPE html>
<html>
<head>

    <title>{{$instance->name}} - Search Results</title>

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
            <div class="panel-body" id="resultsPanelBody">
                <ul class="list-group" id="publicationResults">
                    <li class="list-group-item list-group-item-info">Publications</li>
                    @if(count($publicationResults) > 0)
                    @foreach($publicationResults as $publication)
                    <li class="list-group-item">
                        <a href="{{ URL::to($instance->name.'/archive/'.$publication->id) }}">
                        @if($publication->published == 'N')
                        Unpublished Publication created on {{ $publication->created_at }} - last modified {{ $publication->updated_at }}
                        @else
                        Live Publication published on {{ date('m-d-Y',strtotime($publication->publish_date)) }}
                        @endif
                        </a>
                    </li>
                    @endforeach
                    @else
                    <li class="list-group-item list-group-item-warning">No Publications Found</li>
                    @endif
                </ul>            </div>
            <div class="panel-footer" id="publicationPanelFoot">
            </div>
        </div>
    </div>
</body>
</html>