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
        <div class="row publicationEditor" id="publicationEditor{{$publication->id}}" style="display:none;">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
                <h1 id="publicationTitle{{$publication->id}}"></h1>
                <!-- Now to iterate through the articles -->
                <div class="contentDiv">
                    <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
                    @foreach($publications->articles as $article)
                    <h1 id="articleTitle{{ $article->id }}" class="editable">{{$article->title}}</h1>
                    <p id="articleContent{{ $article->id }}" class="editable">{{$article->content}}<p>
                    <hr/>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="panel-footer" id="publicationPanelFoot">
    </div>
</div>