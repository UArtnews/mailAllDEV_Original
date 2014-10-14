<?php

class DefaultTweakableTableSeeder extends Seeder {

    public function run()
    {
        DB::table('default_tweakable')->truncate();

        $faker = Faker\Factory::create();

        $tweakables = array(
            //CSS First
            'global-background-color'               => array('Editor Background Color', '#19223B', 'color'),
            'publication-background-color'          => array('Article Background Color', '#FFFFFF', 'color'),
            'publication-border-color'              => array('Publication Border Color', '#C8BC9A', 'color'),

            'publication-h1-color'                  => array('H1 Color', '#19223B', 'color'),
            'publication-h1-font'                   => array('H1 Font', "'Helvetica Neue', Helvetica, Arial, sans-serif", 'font'),
            'publication-h1-font-size'              => array('H1 Size', '36px', 'text'),
            'publication-h1-font-weight'            => array('H2 Weight', 'normal', 'weight'),
            'publication-h1-line-height'            => array('H1 Line Height', '100%', 'text'),

            'publication-h2-color'                  => array('H2 Color', '#19223B', 'color'),
            'publication-h2-font'                   => array('H2 Font', "'Helvetica Neue', Helvetica, Arial, sans-serif", 'font'),
            'publication-h2-font-size'              => array('H2 Size', '30px', 'text'),
            'publication-h2-font-weight'            => array('H2 Weight', 'normal', 'weight'),
            'publication-h2-line-height'            => array('H2 Line Height', '100%', 'text'),

            'publication-h3-color'                  => array('H3 Color', '#19223B', 'color'),
            'publication-h3-font'                   => array('H3 Font', "'Helvetica Neue', Helvetica, Arial, sans-serif", 'font'),
            'publication-h3-font-size'              => array('H3 Size', '24px', 'text'),
            'publication-h3-font-weight'            => array('H3 Weight', 'normal', 'weight'),
            'publication-h3-line-height'            => array('H3 Line Height', '100%', 'text'),

            'publication-h4-color'                  => array('H4 Color', '#19223B', 'color'),
            'publication-h4-font'                   => array('H4 Font', "'Helvetica Neue', Helvetica, Arial, sans-serif", 'font'),
            'publication-h4-font-size'              => array('H4 Size', '18px', 'text'),
            'publication-h4-font-weight'            => array('H4 Weight', 'normal', 'weight'),
            'publication-h4-line-height'            => array('H4 Line Height', '100%', 'text'),

            'publication-p-color'                   => array('Paragraph Text Color', 'rgb(0,0,0)', 'color'),
            'publication-p-font'                    => array('Paragraph Font', "'Helvetica Neue', Helvetica, Arial, sans-serif", 'font'),
            'publication-p-font-size'               => array('Paragraph Font Size', '1em', 'text'),
            'publication-p-font-weight'             => array('Paragraph Weight', 'normal', 'weight'),
            'publication-p-line-height'             => array('Paragraph Line Height', '100%', 'text'),

            //Content/Structure Stuff
            'publication-banner-image'              => array('Banner Image URL (ensure banner is not too large!)', 'http://lorempixel.com/500/200', 'text'),
            'publication-width'                     => array('Publication Width', '510px', 'text'),
            'publication-padding'                   => array('Publication Padding', '5px', 'text'),
            'publication-hr-articles'               => array('Horizontal Rule After Articles', true, 'bool'),
            'publication-hr-titles'                 => array('Horizontal Rule After Titles', false, 'bool'),
            'publication-repeated-items'            => array('Automatically Place Repeated Articles at Bottom of Publicaiton', false, 'bool'),
            'publication-headline-summary'          => array('Automatically Generate and Place Headline Summary', false, 'bool'),
            'publication-headline-summary-position' => array('Positioning of Headline Summary', 'center', 'select'),
            'publication-header'                    => array('Header Content', '', 'textarea'),
            'publication-footer'                    => array('Footer Content', 'This publication has been produced by The University of Akron', 'textarea'),
            'publication-repeat-separator'          => array('Repeated Items Separator', '', 'textarea'),
            'publication-repeat-separator-toggle'   => array('Repeated Items Separator', true, 'bool'),

            //Workflow and Features
            'global-accepts-submissions'            => array('Article Submission Enabled', false,'bool'),
        );

        foreach ($tweakables as $parameter => $value)
        {
            DefaultTweakable::create(array(
                'parameter'    => $parameter,
                'display_name' => $value[0],
                'value'        => $value[1],
                'type'         => $value[2],
            ));
        }
    }

}
