<div class="row publicationEditor" id="publicationEditor{{$publication->id}}" style="display:none;">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <h1 id="publicationTitle{{$publication->id}}"></h1>
        <!-- Now to iterate through the articles -->
        <div class="contentDiv">
            <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
            @foreach($publication->articles as $article)
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
            @endforeach
        </div>
    </div>
</div>