<?
$summaryWidth = str_replace('px','',isset($tweakables['publication-headline-summary-width']) ? $tweakables['publication-headline-summary-width'] : $default_tweakables['publication-headline-summary-width']);
?>

body {
    background-color:{{$tweakables['global-background-color'] or $default_tweakables['global-background-color']}};
    color:#222;
    position:relative;
}

a {
    text-decoration:none;
}

p, .article, .aritcleContent {
    color:{{$tweakables['publication-p-color'] or $default_tweakables['publication-p-color']}};
    font-family:{{$tweakables['publication-p-font'] or $default_tweakables['publication-p-font']}};
    font-size:{{$tweakables['publication-p-font-size'] or $default_tweakables['publication-p-font-size']}};
    font-weight:{{$tweakables['publication-p-font-weight'] or $default_tweakables['publication-p-font-weight']}};
    line-height:{{$tweakables['publication-p-line-height'] or $default_tweakables['publication-p-font-size']}};
}

.article {
    overflow:auto;
    padding-bottom:1em;
}

@if(isset($tweakables['publication-hr-articles']))
    @if($tweakables['publication-hr-articles'] == 1)
    .article {
        margin-bottom:1em;
        border-bottom:1px solid #cccccc;
    }
    @endif
@elseif($default_tweakables['publication-hr-articles'] == 1)
    .article {
        margin-bottom:1em;
        border-bottom:1px solid #cccccc;
    }
@endif

h1 {
    color:{{$tweakables['publication-h1-color'] or $default_tweakables['publication-h1-color']}};
    font-family:{{$tweakables['publication-h1-font'] or $default_tweakables['publication-h1-font']}};
    font-size:{{$tweakables['publication-h1-font-size'] or $default_tweakables['publication-h1-font-size']}};
    font-weight:{{$tweakables['publication-h1-font-weight'] or $default_tweakables['publication-h1-font-weight']}};
    line-height:{{$tweakables['publication-h1-line-height'] or $default_tweakables['publication-h1-font-size']}};
}

h2 {
    color:{{$tweakables['publication-h2-color'] or $default_tweakables['publication-h2-color']}};
    font-family:{{$tweakables['publication-h2-font'] or $default_tweakables['publication-h2-font']}};
    font-size:{{$tweakables['publication-h2-font-size'] or $default_tweakables['publication-h2-font-size']}};
    font-weight:{{$tweakables['publication-h2-font-weight'] or $default_tweakables['publication-h2-font-weight']}};
    line-height:{{$tweakables['publication-h2-line-height'] or $default_tweakables['publication-h2-font-size']}};
}

h3 {
    color:{{$tweakables['publication-h3-color'] or $default_tweakables['publication-h3-color']}};
    font-family:{{$tweakables['publication-h3-font'] or $default_tweakables['publication-h3-font']}};
    font-size:{{$tweakables['publication-h3-font-size'] or $default_tweakables['publication-h3-font-size']}};
    font-weight:{{$tweakables['publication-h3-font-weight'] or $default_tweakables['publication-h3-font-weight']}};
    line-height:{{$tweakables['publication-h3-line-height'] or $default_tweakables['publication-h3-font-size']}};
}

h4 {
    color:{{$tweakables['publication-h4-color'] or $default_tweakables['publication-h4-color']}};
    font-family:{{$tweakables['publication-h4-font'] or $default_tweakables['publication-h4-font']}};
    font-size:{{$tweakables['publication-h4-font-size'] or $default_tweakables['publication-h4-font-size']}};
    font-weight:{{$tweakables['publication-h4-font-weight'] or $default_tweakables['publication-h4-font-weight']}};
    line-height:{{$tweakables['publication-h4-line-height'] or $default_tweakables['publication-h4-font-size']}};
}

.colorPanel {
    color:#222;
    background-color:{{$tweakables['publication-border-color'] or $default_tweakables['publication-border-color']}};
}

.contentDiv {
    width:{{ $tweakables['publication-width'] or $default_tweakables['publication-width'] }};
    padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    margin:0em auto;
    position:relative;
    background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
    z-index:0;
}

.publicationBanner {
    margin: 0em auto;
}

.headline-summary-header{
    margin-bottom:.25em;
}

.headline-summary {
    position:relative;
}

.headline-summary-table {
    background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
    padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    width:{{ $tweakables['publication-headline-summary-width'] or $default_tweakables['publication-headline-summary-width'] }};
    vertical-align:top;
}

.headline-summary-left {
    background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
    padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    width:{{ $tweakables['publication-headline-summary-width'] or $default_tweakables['publication-headline-summary-width'] }};
    position:absolute;
    top:-{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    left:{{ isset($tweakables['publication-padding']) ? (-$summaryWidth - (2*str_replace('px','',$tweakables['publication-padding']))) : (-$summaryWidth - (2*str_replace('px','',$default_tweakables['publication-padding']))) }}px;
}

.headline-summary-right {
    background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
    padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    width:{{ $tweakables['publication-headline-summary-width'] or $default_tweakables['publication-headline-summary-width'] }};
    position:absolute;
    top:-{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    left:{{ $tweakables['publication-width'] or $default_tweakables['publication-width'] }};
}

