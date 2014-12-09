<!DOCTYPE html>
<html>
<head>
    <title>The University of Akron Publication Editor</title>

    {{-- TODO: Conditionally load these js/css resources --}}
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>

    @include('editor.editorJavascript')

    {{-- Pull in CSS --}}
    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}"/>
    @include('editor.editorStyle')

    @yield('head')

</head>
<body>
@include('editor.editorNav')
@include('editor.cartModal')
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-xs-12">
        @if(isset($message) && $message != '')
        <div class="editorMessage alert alert-info alert-dismissible" >
            <button type="button" class="close" onclick="$('.editorMessage').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        @if(isset($success) && $success != '')
        <div class="editorSuccess alert alert-success alert-dismissible" >
            <button type="button" class="close" onclick="$('.editorSuccess').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong>{{ $success }}</strong>
        </div>
        @endif
        @if(isset($error) && $error != '')
        <div class="editorError alert alert-danger alert-dismissible" >
            <button type="button" class="close" onclick="$('.editorError').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong>{{ $error }}</strong>
        </div>
        @endif
        @yield('content')
    </div>
</div>

{{-- TODO:  Make this footer useful and modular --}}
<div class="row" style="text-align:center;">
    <a href="{{URL::to('/')}}  ">Publication List</a>
    &nbsp;|&nbsp;
    <a href="{{ URL::to($instance->name) }}">Live Publication View</a>
</div>
</body>
</html>