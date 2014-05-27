<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="articlePanelHead">
        Article Editor
        <button id="backToListArticle" type="button" class="btn btn-primary pull-right btn-xs" onclick="$('.articleEditor').slideUp();$('#articleChooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</button>
    </div>
    <div class="panel-body" id="articlePanelBody">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12" id="articleChooser">
            <table class="table well">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Last Updated</th>
                    <th>Author</th>
                </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                <tr>
                    <td>
                        <a href="#" onclick="$('#articleEditor{{$article->id}}').slideToggle();$('#articleChooser').slideToggle();$('#articleTitle{{$article->id}}').text('{{$article->title}}');">
                            {{$article->title}}
                        </a>
                    </td>
                    <td>
                        {{date('m/d/Y', strtotime($article->created_at))}}
                    </td>
                    <td>
                        {{date('m/d/Y', strtotime($article->updated_at))}}
                    </td>
                    <td>
                        {{User::find($article->author_id)->first}} {{User::find($article->author_id)->last}}
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4">
                        {{$articles->links();}}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        @foreach($articles as $article)
        <div class="row articleEditor" id="articleEditor{{$article->id}}" style="display:none;">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 article">
                <div class="contentDiv">
                    <h3 id="articleTitle{{$article->id}}" class="editable"></h3>
                    <p id="articleContent{{ $article->id }}" class="editable">
                        {{$article->content}}
                    </p>
                    <br/>
                    <table class="table well">
                        <thead>
                        <tr>
                            <th>Date Created</th>
                            <th>Last Updated</th>
                            <th>Author</th>
                            <th>Published Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{date('m/d/Y', strtotime($article->created_at))}}</td>
                            <td>{{date('m/d/Y', strtotime($article->updated_at))}}</td>
                            <td>{{User::find($article->author_id)->first}} {{User::find($article->author_id)->last}}</td>
                            <td>{{$article->published == 'Y' ? 'Published' : 'Not Published';}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="panel-footer" id="articlePanelFoot">
    </div>
</div>