<div class="contentDiv" id="publication{{ $publication->id }}">
    <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}"/>
    @include('publication.publicationHeader')
    @include('publication.headlineSummary')
    @include('publication.articleContainer')
    {{-- Conditional HR's after Titles --}}
    @if((isset($tweakables['publication-repeat-separator-toggle']) && $tweakables['publication-repeat-separator-toggle'] == 1 ) || $default_tweakables['publication-repeat-separator-toggle'] == 1 )
        @if(isset($tweakables['publication-repeat-separator']))
            @if($publication->hasRepeat())
            {{ $tweakables['publication-repeat-separator'] }}
            @endif
        @endif
    @endif
    @include('publication.repeatContainer')
    @include('publication.publicationFooter')
</div>