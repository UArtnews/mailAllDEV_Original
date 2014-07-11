<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="articlePanelHead">
        Article Editor
        <button id="backToListArticle" type="button" class="btn btn-primary pull-right btn-xs" onclick="$('.articleEditor').slideUp();$('#articleChooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</button>
    </div>
    <div class="panel-body" id="articlePanelBody">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12" id="articleChooser">
            <button id="newArticleButton" class="btn btn-primary btn-block" onclick="$('.newArticle').slideToggle();$('#newArticleButton').slideToggle();"><strong>Create New Article</strong></button><br/>
            <div class="newArticle" style="display:none;">
                <div class="contentDiv">
                    <div class="article">
                        <h1 id="newArticleTitle" class="newEditable">[Click here to begin editing Title]</h1>
                        <p id="newArticleContent" class="newEditable">[Click here to begin editing Body]<p>
                        <hr/>
                    </div>
                    <button class="btn btn-success" onclick="saveNewArticle();">Save</button>
                    <button class="btn btn-warning" onclick="$('.newArticle').slideToggle();$('#newArticleButton').slideToggle();cancelNewArticle();">Cancel</button>
                </div>
                <br/><br/>
            </div>
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
                        <a href="#" onclick="addArticleToCart({{ $article->id }})"><span class="badge pull-right alert-success">Add Article to Cart</span></a>
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
                    <div class="article">
                        <h1 id="articleTitle{{ $article->id }}" class="editable">{{$article->title}}</h1>
                        <p id="articleContent{{ $article->id }}" class="editable">{{$article->content}}<p>
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
                            <td>{{User::find($article->author_id)->first}} {{User::find($article->author_id)->last}}</td>
                            <td>{{$article->published == 'Y' ? 'Published' : 'Not Published';}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-block btn-primary" onclick="addArticleToCart({{ $article->id }})">Add To Cart</button>
                    <button class="btn btn-block btn-warning" onclick="deleteArticle({{ $article->id }})">Delete Article</button>
                </div>
            </div>
        </div>
        @endforeach
        @if($subAction != '')
        <div class="row articleEditor" id="articleEditor{{$directArticle->id}}" style="">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 article">
                <div class="contentDiv">
                    <div class="article">
                        <h1 id="articleTitle{{ $directArticle->id }}" class="editable">{{$directArticle->title}}</h1>
                        <p id="articleContent{{ $directArticle->id }}" class="editable">{{$directArticle->content}}<p>
                        <div id="articleIndicator{{ $directArticle->id }}" class="side-indicator">
                            <div id="articleIndicator{{ $directArticle->id }}" class="side-indicator-hitbox">
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
                            <td>{{date('m/d/Y', strtotime($directArticle->created_at))}}</td>
                            <td>{{date('m/d/Y', strtotime($directArticle->updated_at))}}</td>
                            <td>{{User::find($directArticle->author_id)->first}} {{User::find($directArticle->author_id)->last}}</td>
                            <td>{{$directArticle->published == 'Y' ? 'Published' : 'Not Published';}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-block btn-primary" onclick="addArticleToCart({{ $directArticle->id }})">Add To Cart</button>
                    <button class="btn btn-block btn-warning" onclick="deleteArticle({{ $directArticle->id }})">Delete Article</button>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                $('#articleChooser').slideToggle();
            })
        </script>
        @endif
    </div>
    <div class="panel-footer" id="articlePanelFoot">
    </div>
</div>