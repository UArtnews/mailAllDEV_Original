@if(!$isEmail)
{{ isset($tweakables['publication-web-header']) ? str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $tweakables['publication-web-header']) : str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $default_tweakables['publication-web-header']) }}
@endif
{{ isset($tweakables['publication-header']) ? str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)),$tweakables['publication-header']) : str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $default_tweakables['publication-header']) }}
