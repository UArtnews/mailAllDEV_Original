<div class="row publicationEditor" id="publicationEditor{{$publication->id}}" style="display:{{ $display }};">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="well" style="text-align:center;">
            @if($thisPublication->published == 'Y')
                <span class="badge" style="background-color:red;">LIVE</span>
                {{ ucfirst($thisPublication->type) }} Publication published on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
            @else
                <span class="badge" style="">Unpublished</span>
            {{ ucfirst($thisPublication->type) }} Publication was to be published on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
            @endif
        </div>
        <!-- Now to iterate through the articles -->
        <div class="contentDiv" id="publication{{ $publication->id }}">
            <img class="publicationBanner  img-responsive" src="{{$publication->banner_image}}"/>
            @foreach($thisPublication->articles as $article)
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
