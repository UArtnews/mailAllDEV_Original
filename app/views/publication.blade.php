<!DOCTYPE html>
<html>
<head>

    <title>{{$instance->name}} - {{date('m/d/Y', strtotime($publication->publish_date))}}</title>

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    @include('editor.editorStyle',array('default_tweakables' => $default_tweakables, 'tweakables' => $tweakables, 'default_tweakables_names' => $default_tweakables_names))

</head>
<body>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default colorPanel" style="margin-bottom:0px!important;">
            {{-- This is to make the background color fill the entire page --}}
            @include('publication.publicNav', array('instanceName' => $instanceName))
            <div class="panel-body" id="publicationPanelBody">
                @include('publication.staticWebPublication', array('publication' => $publication))
            </div>
            <div class="panel-footer" id="publicationPanelFoot">
                Published on {{ $publication->publish_date }} |  <a href="{{ URL::to($instance->name.'/archive') }}">Archive</a>
            </div>
        </div>
    </div>
</body>
</html>