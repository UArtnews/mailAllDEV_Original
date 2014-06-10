<div class="contentDiv">
    <img class="publicationBanner" src="{{$publication->banner_image}}/?{{rand(1,1000)}}"/>
    @foreach($publication->articles as $article)
    <h1 id="articleTitle{{ $article->id }}">{{$article->title}}</h1>
    <p id="articleContent{{ $article->id }}">{{$article->content}}<p>
    <hr/>
    @endforeach
</div>
