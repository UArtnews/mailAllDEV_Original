<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
        <button id="backToListPublication" type="button" class="btn btn-primary pull-right btn-xs" onclick="$('.publicationEditor').slideUp();$('#publicationChooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</button>
    </div>
    <div class="panel-body" id="publicationPanelBody">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12" id="publicationChooser">
            <div class="well">
                <h3 class="alert-warning" style="text-align:center;">To create a Publication choose a date you wish to publish on.</h3>
                {{ $calendar }}
                <script>
                    //Fixes for calendar
                    $(function(){
                        $(".calendarTable th").first().css('text-align','left');
                        $(".calendarTable th").last().css('text-align','right');
                        $(".calendarTable th[colspan='5'").css('text-align','center').css('font-size', '1.5em');
                    });
                </script>
            </div>
            <table class="table well">
                <thead>
                <tr>
                    <th>Publish Date</th>
                    <th>Creation Date</th>
                    <th>Live Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($publications as $publication)
                <tr>
                    <td>
                        <a href="#" onclick="$('#publicationEditor{{$publication->id}}').slideToggle();$('#publicationChooser').slideToggle();">
                            {{ date('m/d/Y', strtotime($publication->publish_date)) }}
                        </a>
                    </td>
                    <td>{{date('m/d/Y', strtotime($publication->created_at))}}</td>
                    <td>{{ucfirst($publication->type)}} Publication</td>
                    <td>{{$publication->published == 'Y' ? 'Live' : 'Not-Live';}}</td>
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
        @foreach($publications as $publication)
            @include('publication.editableWebPublication', array('thisPublication' => $publication, 'display' => 'none'))
        @endforeach
        @if($subAction != '')
            @if($directIsLoaded)
                <script>
                    //Unhide the chooser and direct publication
                    $(document).ready(function(){
                        $('#publicationEditor'+{{ $subAction }}).slideToggle();
                        $('#publicationChooser').slideToggle();
                    })
                </script>
            @else
                @include('publication.editableWebPublication', array('thisPublication' => $directPublication, 'display' => ''))
                <script>
                    //Unhide the chooser
                    $(document).ready(function(){
                        $('#publicationChooser').slideToggle();
                    })
                </script>
            @endif
        @endif
    </div>
    <div class="panel-footer" id="publicationPanelFoot">
    </div>
</div>