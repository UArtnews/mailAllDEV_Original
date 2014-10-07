<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
        <a href="{{ URL::to("edit/$instanceName/publications") }}" id="backToListPublication" type="button" class="btn btn-primary pull-right btn-xs"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</a>
    </div>
    <div class="panel-body" id="publicationPanelBody">
            @include('publication.editableWebPublication', array('thisPublication' => $publication, 'display' => 'block'))
    </div>
    <div class="panel-footer" id="publicationPanelFoot">
    </div>
</div>