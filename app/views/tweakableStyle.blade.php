body {
    background-color:{{$tweakables['global-background-color'] or $default_tweakables['global-background-color']}};
    color:#222;
    position:relative;
}

p, .article {
    color:{{$tweakables['publication-p-color'] or $default_tweakables['publication-p-color']}};
    font-family:{{$tweakables['publication-p-font'] or $default_tweakables['publication-p-font']}};
    font-size:{{$tweakables['publication-p-font-size'] or $default_tweakables['publication-p-font-size']}};
    font-weight:{{$tweakables['publication-p-font-weight'] or $default_tweakables['publication-p-font-weight']}};
    line-height:{{$tweakables['publication-p-line-height'] or $default_tweakables['publication-p-font-size']}};
}


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

.headline-summary-left {
    background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
    padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    width:225px;
    position:absolute;
    top:0px;
    left:{{ isset($tweakables['publication-padding']) ? (-225 - (2*str_replace('px','',$tweakables['publication-padding']))) : (-225 - (2*str_replace('px','',$default_tweakables['publication-padding']))) }}px;
}

.headline-summary-right {
    background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
    padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
    width:225px;
    position:absolute;
    top:0px;
    left:{{ $tweakables['publication-width'] or $default_tweakables['publication-width'] }};
}

hr {
    margin-top:10px;
    margin-bottom:10px;
    border-top:1px solid #cccccc;
}
