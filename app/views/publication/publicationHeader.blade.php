{{ isset($tweakables['publication-header']) ? str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)),$tweakables['publication-header']) : str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $default_tweakables['publication-header']) }}