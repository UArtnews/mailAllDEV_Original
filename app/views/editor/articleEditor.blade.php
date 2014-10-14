<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="articlePanelHead">
        Article Editor
        <a href="{{ URL::to("edit/$instanceName/articles") }}" id="backToListArticle" type="button" class="btn btn-primary pull-right btn-xs"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</a>
    </div>
    <div class="panel-body" id="articlePanelBody">
        <div class="row articleEditor" id="articleEditor{{$article->id}}">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 article">
                <div class="contentDiv">
                    <div class="article" id="article{{ $article->id }}">
                        <h1 id="articleTitle{{ $article->id }}" class="editable articleTitle">{{stripslashes($article->title)}}</h1>
                        <p id="articleContent{{ $article->id }}" class="editable articleContent">{{stripslashes($article->content)}}<p>
                        <div id="articleIndicator{{ $article->id }}" class="side-indicator">
                            <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
                            </div>
                            &nbsp;&nbsp;&nbsp;Unsaved<br/>
                            &nbsp;&nbsp;&nbsp;Changes
                        </div>
                    </div>
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
                            <td>{{ User::where('uanet', $article->author)->first() or '' }}</td>
                            @if($article->submission == 'Y')
                            <td><span class="badge alert-warning">Submitted</span></td>
                            @else
                            <td></td>
                            @endif
                        </tr>
                        </tbody>
                        @if($article->submission == 'Y')
                        <thead>
                        <tr>
                            <th colspan="4">Issue Dates</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="4">
                                {{ str_replace(',',', ',str_replace(']','',str_replace('[','',str_replace('"','', stripslashes($article->issue_dates))))) }}
                            </td>
                        </tr>
                        </tbody>
                        @endif
                    </table>
                    <button class="btn btn-block btn-primary" onclick="addArticleToCart({{ $article->id }})">Add To Cart</button>
                    <button class="btn btn-block btn-warning" onclick="deleteArticle({{ $article->id }})">Delete Article</button>
                </div>
            </div>
        </div>
    </div>
</div>