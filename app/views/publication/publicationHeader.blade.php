@if(!$isEmail)
{{ isset($tweakables['publication-web-header']) ? str_replace('**DATE**',date('F j, Y'),$tweakables['publication-web-header']) : '' }}
@endif
{{ isset($tweakables['publication-header']) ? str_replace('**DATE**',date('F j, Y'),$tweakables['publication-header']) : '' }}