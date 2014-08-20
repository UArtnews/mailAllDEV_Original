<!DOCTYPE html>
<html>
<head>
    <title>{{$instance->name}} Article Submission Form</title>

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}" />
    @include('editor.editorStyle')

</head>
<body>
@include('publication.publicNav', array('instanceName' => $instanceName))
<div class="row">
    <div class="well col-lg-8 col-md-offset-2 col-md-10 col-md-offset-1 col-sm-12">
        {{ Form::open(array('url' => '#', 'method' => 'post', 'class' => 'form')) }}
        <h3>Your Announcement <small>Click on the Headline or Body to begin editing</small></h3>
        <div class="well colorPanel">
            <div class="contentDiv">
                <img src="{{ isset($tweakables['publication-banner-image']) ? $tweakables['publication-banner-image'] : $default_tweakables['publication-banner-image']  }}"/>
                <div class="article" id="article">
                    <h1 id="articleTitle" class="editable articleTitle" contenteditable="true">Your Headline (Click here to edit)</h1>
                    <p id="articleContent" class="editable articleContent" contenteditable="true">Your Announcement Body (Click here to edit)<p>
                </div>
            </div>
        </div>
        <h3><small>Note:  The dimensions of this article will match the dimensions in the final publication.</small></h3>
        <br/>

        <h3>Announcement Information</h3>
        {{ Form::label('event_start_date', 'Start Date: ') }}
        <span id='event_start_date_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('event_start_date', null, array('class' => 'datePicker form-control')) }}
        <br/>

        {{ Form::label('event_end_date', 'End Date: ') }}
        <span id='event_end_date_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('event_end_date', null, array('class' => 'datePicker form-control')) }}
        <br/>

        {{ Form::label('start_time', 'Start Time: ') }}
        <span id='start_time_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('start_time', null, array('class' => 'timePicker form-control')) }}
        <br/>

        {{ Form::label('end_time', 'End Time: ') }}
        <span id='end_time_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('end_time', null, array('class' => 'timePicker form-control')) }}
        <br/>

        {{ Form::label('location', 'Location: ') }}
        <span id='location_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('location', null, array('class' => 'form-control')) }}
        <br/>

        <h3>What Issue Would You Like This Announcement To Appear</h3>
        <ul class="list-group">
            @foreach($publications as $publication)
            <li class="list-group-item">{{ Form::checkbox('publish_dates', $publication->publish_date) }}&nbsp;&nbsp;{{ date('m/d/Y', strtotime($publication->publish_date)) }}</li>
            @endforeach
        </ul>
        <br/>
        <h3>Your Information</h3>
        {{ Form::label('name', 'Name: ') }}
        <span id='name_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('name', null, array('class' => 'form-control')) }}
        <br/>

        {{ Form::label('email', 'Email: ') }}
        <span id='email_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('email', null, array('class' => 'form-control')) }}
        <br/>

        {{ Form::label('phone', 'Phone: ') }}<small>&nbsp;&nbsp;&nbsp;Phone Numbers will not be printed in publications</small>
        <span id='phone_error' class="label label-danger" style="display:none;"></span>
        {{ Form::text('phone', null, array('class' => 'form-control')) }}
        <br/>

        {{ Form::label('organization', 'Registered Student Organization: ') }}
        {{ Form::text('organization', null, array('class' => 'form-control')) }}
        <br/>

        {{ Form::label('department', 'Campus Department: ') }}
        {{ Form::text('department', null, array('class' => 'form-control')) }}
        <br/>

        {{ Form::checkbox('publish_contact_info', false, array('class' => 'form-control')) }}
        {{ Form::label('publish_contact_info', 'I want to publish this contact information: ') }}
        <br/>
        <br/>
        {{ Form::submit('submit',array('style' => 'display:none;')) }}
        {{ Form::close() }}
        <button id="submitAnnouncement" class="btn btn-success btn-block" onclick="saveSubmission()">Submit Announcement</button>
        <script>
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
                    url: '{{ URL::to('resource/submission') }}',
                    type: 'post',
                    data: {
                        'instance_id': '{{ $instance->id }}',
                        'title': $('#articleTitle').html(),
                        'content': $('#articleContent').html(),
                        'event_start_date': $('#event_start_date').val(),
                        'event_end_date': $('#event_end_date').val(),
                        'start_time': $('#start_time').val(),
                        'end_time': $('#start_time').val(),
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
</div>
</body>
</html>