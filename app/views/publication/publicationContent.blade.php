<div class="contentDiv" id="publication{{ $publication->id }}">
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) != 'center')
        @include('publication.headlineSummary')
    @endif
    @if(strlen($publication->banner_image) > 0)
    <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}" {{ $isEmail ? 'align="center"' : '' }}/>
    @endif
    @include('publication.publicationHeader')
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
        @include('publication.headlineSummary')
    @endif
    @include('publication.articleContainer')
    {{-- Conditional HR's after Titles --}}
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 1)
        @if(isset($tweakables['publication-repeat-separator']))
            @if($publication->hasRepeat())
            {{ $tweakables['publication-repeat-separator'] }}
            @endif
        @endif
    @endif
    @include('publication.repeatContainer')
    @include('publication.publicationFooter')
</div>