@if( (isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary']) == 1)
    {{-- Center Headline Summary --}}
    <div class="headline-summary">
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
        <div class="headline-summary headline-summary-center">
            <h3 class="headline-summary-header">Today's News:</h3>
            @foreach($publication->articles as $article)
                <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
            @endforeach
        </div>
    {{-- Left/Right Hand Headline Summary --}}
    @else
        <div class="headline-summary headline-summary-{{ isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position'] }}">
            <h3 class="headline-summary-header">Today's News:</h3>
            @foreach($publication->articles as $article)
                <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
            @endforeach
        </div>
    @endif
    </div>
@endif