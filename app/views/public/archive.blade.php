@extends('public.master')

@section('content')
<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
    <div class="panel panel-default colorPanel">
        <div class="panel-heading" id="publicationPanelHeading">Archives
            <button class="btn btn-xs btn-primary pull-right" onclick="$('.contentDiv').slideUp();$('.chooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp;Back to Archive Listing</button>
        </div>
        <div class="panel-body" id="publicationPanelBody">
            {{ Form::open(array('url' => URL::to($instanceName.'/search'), 'method' => 'get', 'class' => 'form-inline')) }}
            {{ Form::label('search', 'Search Term:') }}
            {{ Form::text('search', isset($searchValue) ? $searchValue : null, array('class' => 'form-control')) }}
            &nbsp;&nbsp;
            {{ Form::label('year', 'Year:') }}
            {{ Form::select('year', $years, null, array('class' => 'form-control')) }}
            &nbsp;&nbsp;
            {{ Form::label('month', 'Month:') }}
            {{ Form::select('month', $months, null, array('class' => 'form-control')) }}
            {{ Form::submit('Search', array('class' => 'form-control')) }}
            {{ Form::close() }}<br/>
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
                        <a href="{{ URL::to($instanceName.'/archive/'.$publication->id) }}">
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
        </div>
        <div class="panel-footer" id="publicationPanelFoot">
            Published on {{ $publication->publish_date }} |  <a href="{{ URL::to($instance->name.'/archive') }}">Archive</a>
        </div>
    </div>
</div>
@stop