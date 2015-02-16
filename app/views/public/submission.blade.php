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
    {{ Form::open(array('url' => '#', 'method' => isset($article) ? 'put' : 'post', 'class' => 'form')) }}
    <h3>Your Announcement <small>Click on the Headline or Body to begin editing</small></h3>
    <div class="well colorPanel">
        <div class="contentDiv">
            <img src="{{ isset($tweakables['publication-banner-image']) ? $tweakables['publication-banner-image'] : $default_tweakables['publication-banner-image']  }}" class="img-responsive"/>
            <div class="article" id="article">
                <h1 id="articleTitle" class="submissionEditor articleTitle" contenteditable="true">
                @if(isset($article))
                    {{ stripslashes($article->title) }}
                @else
                    Your Announcement Title (Click here to edit)
                @endif
                </h1>
                <div id="articleContent" class="submissionEditor articleContent" contenteditable="true">
                @if(isset($article))
                    {{ stripslashes($article->content) }}
                @else
                    Your Announcement Body (Click here to edit)
                @endif
                </div>
            </div>
        </div>
    </div>
    <h3><small>Note:  The dimensions of this article will match the dimensions in the final publication.</small></h3>
    <br/>

    <h3>Announcement Information</h3>
    {{ Form::label('event_start_date', 'Start Date: ') }}
    <span id='event_start_date_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('event_start_date', isset($article) ? $article->event_start_date : null, array('class' => 'datePicker form-control')) }}
    <br/>

    {{ Form::label('event_end_date', 'End Date: ') }}
    <span id='event_end_date_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('event_end_date', isset($article) ? $article->event_end_date : null, array('class' => 'datePicker form-control')) }}
    <br/>

    {{ Form::label('start_time', 'Start Time: ') }}
    <span id='start_time_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('start_time', isset($article) ? $article->start_time : null, array('class' => 'timePicker form-control')) }}
    <br/>

    {{ Form::label('end_time', 'End Time: ') }}
    <span id='end_time_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('end_time', isset($article) ? $article->end_time : null, array('class' => 'timePicker form-control')) }}
    <br/>

    {{ Form::label('location', 'Location: ') }}
    <span id='location_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('location', isset($article) ? $article->location : null, array('class' => 'form-control')) }}
    <br/>

    <h3>What Issue Would You Like This Announcement To Appear</h3>
    <ul class="list-group">
    @if(count($publications) > 0)
        @foreach($publications as $publication)
            @if(isset($issue_dates))
            <li class="list-group-item">{{ Form::checkbox('publish_dates', $publication->publish_date, in_array($publication->publish_date,$issue_dates)) }}&nbsp;&nbsp;{{ date('m/d/Y', strtotime($publication->publish_date)) }}</li>
            @else
            <li class="list-group-item">{{ Form::checkbox('publish_dates', $publication->publish_date) }}&nbsp;&nbsp;{{ date('m/d/Y', strtotime($publication->publish_date)) }}</li>
            @endif
        @endforeach
    @else
        <li class="list-group-item">No upcoming publications!</li>
    @endif
    </ul>
    <br/>
    <h3>Your Information</h3>
    {{ Form::label('name', 'Name: ') }}
    <span id='name_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('name', isset($_SERVER['cn']) ? $_SERVER['cn'] : null, array('class' => 'form-control')) }}
    <br/>

    {{ Form::label('email', 'Email: ') }}
    <span id='email_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('email', isset($_SERVER['mail']) ? $_SERVER['mail'] : null, array('class' => 'form-control')) }}
    <br/>

    {{ Form::label('phone', 'Phone: ') }}<small>&nbsp;&nbsp;&nbsp;Phone Numbers will not be printed in publications</small>
    <span id='phone_error' class="label label-danger" style="display:none;"></span>
    {{ Form::text('phone', isset($article) ? $article->phone : null, array('class' => 'form-control')) }}
    <br/>

    {{ Form::label('organization', 'Registered Student Organization: ') }}
    {{ Form::text('organization', isset($article) ? $article->organization : null, array('class' => 'form-control')) }}
    <br/>

    {{ Form::label('department', 'Campus Department: ') }}
    {{ Form::text('department', isset($article) ? $article->department : null, array('class' => 'form-control')) }}
    <br/>

    {{ Form::checkbox('publish_contact_info', false, array('class' => 'form-control')) }}
    {{ Form::label('publish_contact_info', 'I want to publish this contact information: ') }}
    <br/>
    <br/>
    {{ Form::close() }}
    <button id="submitAnnouncement" class="btn btn-success btn-block" onclick="saveSubmission()">Submit Announcement</button>
    <script>
        CKEDITOR.disableAutoInline = true;
        $('.submissionEditor').click(function(){
            console.log(this);
            CKEDITOR.inline(this, {
                toolbar: [
                     { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                     { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                     { name: 'others', items: [ '-' ] },
                     { name: 'about', items: [ 'About' ] },
                     '/',
                     { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', '-', 'RemoveFormat' ] },
                     { name: 'styles', items: ['Format'] },
                     { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                     { name: 'links', items: [ 'Link', 'Unlink' ] },
                     { name: 'insert', items: [ 'Image'] },
                     { name: 'others', items: [ '-' ] },
                     { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                 ]
            });
        })

        $(function (){
            $('.datePicker').datetimepicker({
                pickTime: false
            });
            $('.timePicker').datetimepicker({
                pickDate: false
            });

        });

        function saveSubmission(){

            var onclick = $('#submitAnnouncement').attr('onclick');

            $('#submitAnnouncement').attr('onclick','');

            var issue_dates = new Array();
            //Get all the checked dates
            $('input[name="publish_dates"]:checked').each(function(index, elem){
                issue_dates.push($(elem).val());
            });

            if($('input[name="publish_contact_info"]:checked').length > 0){
                var publish_contact_info = 'Y';
            }else{
                var publish_contact_info = 'N';
            }

            $.ajax({
                url: '{{ URL::to('resource/submission') }}' + '{{ isset($article) ? '/'.$article->id : '/' }}',
                type: '{{ isset($article) ? 'put' : 'post' }}',
                data: {
                    'instance_id': '{{ $instance->id }}',
                    'title': $('#articleTitle').html(),
                    'content': $('#articleContent').html(),
                    'event_start_date': $('#event_start_date').val(),
                    'event_end_date': $('#event_end_date').val(),
                    'start_time': $('#start_time').val(),
                    'end_time': $('#end_time').val(),
                    'location': $('#location').val(),
                    'issue_dates': JSON.stringify(issue_dates),
                    'name': $('#name').val(),
                    'email': $('#email').val(),
                    'phone': $('#phone').val(),
                    'organization': $('#organization').val(),
                    'department': $('#department').val(),
                    'publish_contact_info': publish_contact_info
                }
            }).done(function(data){
                console.log(data);
                if(data['success']){
                    location = '{{ URL::to('resource/submission/') }}/'+data.submission_id;
                }else if(data['error']){
                    $('.label-danger').hide();
                    window.scrollTo(0,0);
                    $.each(data['messages'], function(id,value){
                        $("#"+id+"_error").text('Required Field!  Please correct and resubmit').show();
                    });
                }

                $('#submitAnnouncement').attr('onclick', onclick);

        });
        }
    </script>
</div>
@stop