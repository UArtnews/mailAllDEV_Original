@extends('public.master')

@section('head')
<script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
<link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}" />
<script src="{{ URL::to('js/moment.js') }}"></script>
<script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
@stop

@section('content')
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
<div class="well col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus venenatis orci sed sem rutrum, eget sagittis augue sollicitudin. Cras placerat aliquam nibh, at lobortis turpis consectetur id. Maecenas ligula augue, congue suscipit malesuada nec, auctor varius quam. In vel urna sit amet tellus elementum ornare id et felis. Etiam condimentum erat neque, vitae consectetur elit semper vitae. Donec ullamcorper odio ac semper consectetur. Cras nec finibus ligula.
    </p>
    <p>
        Etiam eleifend felis sed tellus lobortis imperdiet. Curabitur sit amet felis aliquet, dapibus neque eget, consectetur magna. Fusce ornare, nisl vitae euismod consequat, diam nisi euismod dolor, id scelerisque leo ligula a ligula. Integer accumsan orci vel justo tincidunt malesuada. Nullam quis leo lacinia, elementum massa in, tristique mi. Aliquam quis hendrerit lorem. Quisque elementum, quam non euismod elementum, mi risus aliquet velit, dapibus malesuada eros lacus id sem. Nunc pretium dignissim nisl, id dapibus magna. Vestibulum massa diam, aliquet eu velit nec, gravida congue dui.
    </p>
    <p>
        Praesent lorem est, egestas gravida arcu sed, pharetra efficitur massa. Donec dapibus efficitur ipsum. Integer nisi libero, cursus a pharetra sed, maximus ac nisl. Nulla euismod nisi nec sapien semper consequat. Pellentesque ornare, elit sit amet porttitor porta, est quam aliquet sem, tempor efficitur risus massa a ipsum. Integer suscipit non ante aliquam varius. Sed ligula ante, gravida vitae laoreet sit amet, finibus a diam. Suspendisse interdum nulla non gravida consectetur. In ac nisi mi. Praesent non metus sed libero aliquet interdum. Nulla metus tellus, tempus eu euismod sed, laoreet sit amet erat. Phasellus in egestas nunc. In faucibus pellentesque congue. Integer vehicula non urna non dapibus. Vestibulum vitae porttitor libero. Sed euismod, turpis sit amet volutpat sagittis, risus tortor gravida tellus, id dignissim velit felis ut justo.
    </p>
    <p>
        Nam mollis malesuada tincidunt. Phasellus laoreet, sem ut condimentum pretium, nulla lacus ullamcorper tellus, feugiat facilisis orci tortor interdum tellus. Curabitur et ex quis est interdum laoreet. Praesent a magna at felis feugiat ornare. Cras sed tempor magna, nec dapibus nibh. Sed porttitor nisi risus, eu venenatis eros volutpat in. Nullam sit amet ligula eu velit sollicitudin sodales. Morbi eleifend aliquam felis, vitae accumsan nisi euismod eu. Pellentesque in orci porta, interdum tortor a, convallis lacus. Cras sed vehicula justo. Praesent et lacus sit amet mauris placerat egestas non vitae est.
    </p>
    <p>
        Donec eu ornare urna. Maecenas scelerisque urna vitae maximus imperdiet. Praesent congue sit amet dui consectetur faucibus. Vestibulum pulvinar semper mauris in tempor. Fusce ut arcu sed ante dapibus malesuada at et augue. Aenean id egestas diam. Suspendisse vestibulum accumsan enim, sed hendrerit mauris rutrum at. Nulla imperdiet aliquam odio sit amet luctus.
    </p>
    <hr/>
    <a type="button" class="btn btn-danger btn-lg" href="{{ URL::to($instanceName) }}">I Do Not Agree</a>
    <a type="button" class="btn btn-primary btn-lg" href="{{ URL::to('submit/'.$instanceName) }}">I Agree</a>
</div>
@stop