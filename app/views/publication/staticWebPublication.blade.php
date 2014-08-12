<div class="contentDiv" id="publication{{ $publication->id }}" @if(isset($hide)) style="display:none;" @endif>
    <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}"/>
    {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
    {{-- Insert Article Summary Conditionally --}}
    @if( isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
        <h3>Today's Headlines:</h3>
        @foreach($publication->articles as $article)
            <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
        @endforeach
    @endif
    @foreach($publication->articles as $article)
        @include('snippet.article', array('contentEditable' => false))
    @endforeach
    {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
</div>
