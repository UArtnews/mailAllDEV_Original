<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
        <button id="backToListPublication" type="button" class="btn btn-primary pull-right btn-xs" onclick="$('.publicationEditor').slideUp();$('#publicationChooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</button>
    </div>
    <div class="panel-body" id="publicationPanelBody">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12" id="publicationChooser">
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
                        <a href="#" onclick="$('#publicationEditor{{$publication->id}}').slideToggle();$('#publicationChooser').slideToggle();$('#publicationTitle{{$publication->id}}').text('Now Viewing {{$instanceName}} - {{date('m/d/Y', strtotime($publication->created_at))}}');">
                            {{date('m/d/Y', strtotime($publication->publish_date))}}
                        </a>
                    </td>
                    <td>{{date('m/d/Y', strtotime($publication->created_at))}}</td>
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
            @include('publication.editableWebPublication', array('publication' => $publication, 'display' => 'none'))
        @endforeach
    </div>
    <div class="panel-footer" id="publicationPanelFoot">
    </div>
</div>