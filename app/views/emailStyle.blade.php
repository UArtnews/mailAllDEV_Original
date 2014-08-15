<?
//Calculate Image width (I hate this part)
if(isset($tweakables['publication-width'])){
    $width = str_replace('px','',$tweakables['publication-width']);
}else{
    $width = str_replace('px','',$default_tweakables['publication-width']);
}
if(isset($tweakables['publication-padding'])){
    $padding = str_replace('px','',$tweakables['publication-padding']);
}else{
    $padding = str_replace('px','',$default_tweakables['publication-padding']);
}
$width = ($width - 2 * $padding) . 'px';
?>
<style type="text/css">

    /* Ink styles go here in production */

</style>
<style>

    .body {
        color:#222;
        position:relative;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    .headline-summary-header{
        margin-bottom:.25em;
    }

    a {
        color:rgb(66, 139, 202);
        text-decoration: none solid rgb(66, 139, 202);
        font-size:{{$tweakables['publication-p-font-size'] or $default_tweakables['publication-p-font-size']}};
    }

    body {
        background-color:{{$tweakables['global-background-color'] or $default_tweakables['global-background-color']}};
        color:#222;
        position:relative;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    p {
        color:{{$tweakables['publication-p-color'] or $default_tweakables['publication-p-color']}};
        font-size:{{$tweakables['publication-p-font-size'] or $default_tweakables['publication-p-font-size']}};
        line-height:{{$tweakables['publication-p-line-height'] or $default_tweakables['publication-p-font-size']}};

        {{-- Bootstrap Fix --}}

    }

    h1 {
        color:{{$tweakables['publication-h1-color'] or $default_tweakables['publication-h1-color']}};
        font-size:{{$tweakables['publication-h1-font-size'] or $default_tweakables['publication-h1-font-size']}};
        line-height:{{$tweakables['publication-h1-line-height'] or $default_tweakables['publication-h1-font-size']}};
        font-weight:500;
    }

    h2 {
        color:{{$tweakables['publication-h2-color'] or $default_tweakables['publication-h2-color']}};
        font-size:{{$tweakables['publication-h2-font-size'] or $default_tweakables['publication-h2-font-size']}};
        line-height:{{$tweakables['publication-h2-line-height'] or $default_tweakables['publication-h2-font-size']}};
        font-weight:500;
    }

    h3 {
        color:{{$tweakables['publication-h3-color'] or $default_tweakables['publication-h3-color']}};
        font-size:{{$tweakables['publication-h3-font-size'] or $default_tweakables['publication-h3-font-size']}};
        line-height:{{$tweakables['publication-h3-line-height'] or $default_tweakables['publication-h3-font-size']}};
        font-weight:500;
    }

    h4 {
        color:{{$tweakables['publication-h4-color'] or $default_tweakables['publication-h4-color']}};
        font-size:{{$tweakables['publication-h4-font-size'] or $default_tweakables['publication-h4-font-size']}};
        line-height:{{$tweakables['publication-h4-line-height'] or $default_tweakables['publication-h4-font-size']}};
        font-weight:500;
    }

    .colorPanel {
        color:#222;
        background-color:{{$tweakables['publication-border-color'] or $default_tweakables['publication-border-color']}};
    }

    .contentDiv {
        @if($isEmail)
        width:{{$width}};
        @else
        width:{{ $tweakables['publication-width'] or $default_tweakables['publication-width'] }};
        @endif
        padding:{{ $tweakables['publication-padding'] or $default_tweakables['publication-padding'] }};
        margin:0em auto;
        position:relative;
        background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
        z-index:0;
        box-sizing: border-box;
    }
    hr {
        margin-top: 20px;
        margin-bottom: 20px;
        border: 0;
        border-top: 1px solid #eee;
    }
</style>