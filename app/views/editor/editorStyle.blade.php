<style>
    body {
        background-color:{{$tweakables['global-background-color'] or $default_tweakables['global-background-color']}};
        color:#222;
        position:relative;
    }

    p {
    color:{{$tweakables['publication-p-color'] or $default_tweakables['publication-p-color']}};
    font-size:{{$tweakables['publication-p-font-size'] or $default_tweakables['publication-p-font-size']}};
    line-height:{{$tweakables['publication-p-line-height'] or $default_tweakables['publication-p-font-size']}};
    }

    h1 {
    color:{{$tweakables['publication-h1-color'] or $default_tweakables['publication-h1-color']}};
    font-size:{{$tweakables['publication-h1-font-size'] or $default_tweakables['publication-h1-font-size']}};
    line-height:{{$tweakables['publication-h1-line-height'] or $default_tweakables['publication-h1-font-size']}};
    }

    h2 {
    color:{{$tweakables['publication-h2-color'] or $default_tweakables['publication-h2-color']}};
    font-size:{{$tweakables['publication-h2-font-size'] or $default_tweakables['publication-h2-font-size']}};
    line-height:{{$tweakables['publication-h2-line-height'] or $default_tweakables['publication-h2-font-size']}};
    }

    h3 {
    color:{{$tweakables['publication-h3-color'] or $default_tweakables['publication-h3-color']}};
    font-size:{{$tweakables['publication-h3-font-size'] or $default_tweakables['publication-h3-font-size']}};
    line-height:{{$tweakables['publication-h3-line-height'] or $default_tweakables['publication-h3-font-size']}};
    }

    h4 {
    color:{{$tweakables['publication-h4-color'] or $default_tweakables['publication-h4-color']}};
    font-size:{{$tweakables['publication-h4-font-size'] or $default_tweakables['publication-h4-font-size']}};
    line-height:{{$tweakables['publication-h4-line-height'] or $default_tweakables['publication-h4-font-size']}};
    }

    input {
        color:#222;
        box-shadow:0 0 30px rgba(0,0,0,0.3);
        border:0px;
        padding:1px;
        font-weight:700;
    }

    #publicationPanelFoot {
        text-align:center;
    }

    .colorPanel {
        color:#222;
    background-color:{{$tweakables['publication-border-color'] or $default_tweakables['publication-border-color']}};
    }

    .error {
        color:red;
        font-weight:bold;
    }

    #logo {
        margin-bottom:1.5em;
    }

    .centerMe {
        margin:0em auto;
        text-align:center;
    }

    .logo:hover {
        box-shadow: 0 0 30px rgba(255,255,255,.6);
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
        margin-bottom:1em;
    }

    .editorSaveRevert {
        background-color:rgba(255,255,255,0.5);
        padding:5px;
        position:absolute;
        width:100px;
    }

    .side-indicator-hitbox {
        height:100%;
        width:200%;
        left:-10px;
        position:absolute;
        z-index:-2;
    }

    .side-indicator {
        width:10px;
        opacity:0;
        position:absolute;
        left:-10px;
        color:white;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        z-index:-1;
        overflow:hidden;
    }


    @-webkit-keyframes slideout {
        0% {
            width:10px;
            left:-10px;
        }
        100% {
            width:100px;
            left:-100px
        }
    }

    @-webkit-keyframes slidein {
        0% {
            width:100px;
            left:-100px
        }
        100% {
            width:10px;
            left:-10px;
        }
    }

    @-webkit-keyframes fadeout {
        0% {
            opacity:0.5;
        }
        100% {
            opacity:0;
        }
    }
    @-webkit-keyframes fadein {
        0% {
            opacity:0;
        }
        100% {
            opacity:0.5;
        }
    }



</style>