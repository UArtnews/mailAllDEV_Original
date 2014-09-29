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
    <div class="well colorPanel">
        <div class="contentDiv">
            <div class="alert alert-info">
                Thank you for your submission.  You may return to this URL to check on the status of your submission any time.
            </div>
            <div class="submission" id="submission{{ $article->id }}">
                <img src="{{ isset($tweakables['publication-banner-image']) ? $tweakables['publication-banner-image'] : $default_tweakables['publication-banner-image']  }}"/>
                <h1 id="submissionTitle{{ $article->id }}" class="articleTitle">{{ stripslashes($article->title) }}</h1>
                <p id="submissionContent{{ $article->id }}" class="articleContent">{{ stripslashes($article->content) }}<p>
            </div>
            <table class="table well" >
                <thead>
                <tr>
                    <th>Date Created</th>
                    <th colspan="2">Submitter</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{date('m/d/Y', strtotime($article->created_at))}}</td>
                    <td colspan="2">{{ $article->name }}<br/>{{ $article->email }}</td>
                    <td>{{$article->phone }}</td>
                </tr>

                </tbody>
                <thead>
                <tr>
                    <th colspan="2">Issue Dates</th>
                    <th>Location</th>
                    <th>Publish Contact Info?</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="2">
                        {{ str_replace(',',', ',str_replace(']','',str_replace('[','',str_replace('"','', stripslashes($article->issue_dates))))) }}
                    </td>
                    <td>{{ $article->location }}</td>
                    <td>
                        {{ $article->publish_contact_info == 'Y' ? 'Yes' : 'No' }}
                    </td>
                </tr>

                </tbody>
                <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ date('m/d/Y', strtotime($article->event_start_date)) }}</td>
                    <td>{{ date('m/d/Y', strtotime($article->event_end_date)) }}</td>
                    <td>{{ date('g:i a',strtotime($article->start_time)) }}</td>
                    <td>{{ date('g:i a',strtotime($article->end_time)) }}</td>
                </tr>
                <tr>
                    @if($article->promoted == 'N')
                    <td class="alert alert-warning" colspan="4">This submission as not been accepted yet.</td>
                    @else
                    <td class="alert alert-success" colspan="4">This submission has been accepted!</td>
                    @endif
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
</body>
</html>