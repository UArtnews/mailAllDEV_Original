<div class="contentDiv" id="publication{{ $publication->id }}">
    <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
    @foreach($publication->articles as $article)
    <div class="article" id="article{{ $article->id }}">
        <h1 id="articleTitle{{ $article->id }}" class="editable articleTitle">{{ stripslashes($article->title) }}</h1>
        <p id="articleContent{{ $article->id }}" class="editable articleContent">{{ stripslashes($article->content) }}<p>
        <div id="articleIndicator{{ $article->id }}" class="side-indicator">
            <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
            </div>
            &nbsp;&nbsp;&nbsp;Unsaved<br/>
            &nbsp;&nbsp;&nbsp;Changes
        </div>
    </div>
    @endforeach
</div>
