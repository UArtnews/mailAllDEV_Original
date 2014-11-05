@extends('public.master')

@section('content')
<div class="panel-body" id="publicationPanelBody">
    @include('publication.master', array('shareIcons' => false, 'isEditable' => false, 'isEmail' => false))
</div>
<div class="panel-footer" id="publicationPanelFoot">
    Published on {{ $publication->publish_date }} |  <a href="{{ URL::to($instance->name.'/archive') }}">Archive</a>
</div>
@stop