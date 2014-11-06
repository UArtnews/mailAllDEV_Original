<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    @if(!$isEmail)
        @include('emailStyle')
    @endif
</head>
<body class="colorPanel">
    <table class="colorPanel" width="100%" align="center">
        <tr>
            <td>
                <table style="margin:0px auto" align="center">
                    <tr>
                    @if((isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'left')
                        @if(isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                        <td class="headline-summary-table">
                            @include('publication.headlineSummary')
                        </td>
                        @endif
                    @endif
                        <td class="contentDiv" id="publication{{ $publication->id }}" >
                            @if(strlen($publication->banner_image) > 0)
                            <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}"/>
                            @endif
                            @include('publication.publicationWebHeader')
                            @if((isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
                                @if(isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                                    @include('publication.headlineSummary')
                                @endif
                            @endif
                            @include('publication.publicationHeader')
                            @include('publication.articleContainer')
                            {{-- Conditional Separator --}}
                            @if((isset($tweakables['publication-repeat-separator-toggle']) && $tweakables['publication-repeat-separator-toggle'] == 1 ) || $default_tweakables['publication-repeat-separator-toggle'] == 1 )
                                @if(isset($tweakables['publication-repeat-separator']))
                                    {{ $tweakables['publication-repeat-separator'] }}
                                @endif
                            @endif
                            @include('publication.repeatContainer')
                            @include('publication.publicationFooter')
                        </td>
                    @if((isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'right')
                        @if(isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                        <td class="headline-summary-table">
                            @include('publication.headlineSummary')
                        </td>
                        @endif
                    @endif
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>