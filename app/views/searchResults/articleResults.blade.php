<ul class="list-group" id="articleResults">
    <li class="list-group-item list-group-item-info">Articles</li>
    @if($articleResults->count() > 0)
    @foreach($articleResults as $article)
    <li class="list-group-item"><a href="{{ URL::to("edit/$instanceName/articles/$article->id") }}">{{ $article->title }} - By {{User::find($article->author_id)->first}} {{User::find($article->author_id)->last}} - {{ $article->created_at }}</a></li>
    @endforeach
    @else
    <li class="list-group-item list-group-item-warning">No Articles Found</li>
    @endif
</ul>