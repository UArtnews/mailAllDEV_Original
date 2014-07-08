<div class="row publicationEditor" id="publicationEditor{{$publication->id}}" style="display:{{ $display }};">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="well" style="text-align:center;">
            {{ $publication->published == 'Y' ? '<span class="badge" style="background-color:red;">LIVE</span>' : '<span class="badge">Unpublished</span>' }}
            Publication published on {{ date('m-d-Y',strtotime($publication->publish_date)) }}
        </div>
        <!-- Now to iterate through the articles -->
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
    </div>
</div>
