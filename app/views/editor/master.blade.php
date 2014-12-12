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
        @include('public.messages')
        @yield('content')
    </div>
</div>

{{-- TODO:  Make this footer useful and modular --}}
<div class="row" style="text-align:center;">
    <a href="{{URL::to('/')}}  ">Publication List</a>
    &nbsp;|&nbsp;
    <a href="{{ URL::to($instance->name) }}">Live Publication View</a>
</div>
{{--<div class="row" style="text-align:center;">--}}
    {{--<ul class="list-group">--}}
    {{--@foreach(DB::getQueryLog() as $query)--}}
        {{--<li class="list-group-item">--}}
            {{--{{ var_dump($query) }}--}}
            {{--{{ $query['query'] }} : @foreach($query['bindings'] as $bind){{ $bind }},@endforeach : {{ $query['time'] }}ms--}}
        {{--</li>--}}
    {{--@endforeach--}}
    {{--</ul>--}}
{{--</div>--}}
</body>
</html>