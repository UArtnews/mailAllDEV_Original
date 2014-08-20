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
@include('editor.editorStyle')
<style>
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