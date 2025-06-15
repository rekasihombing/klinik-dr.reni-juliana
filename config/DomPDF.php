<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf

    'public_path' => null,  // Override the public path if needed

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => [
        /**
         * Default paper size
         */
        'default_paper_size' => 'a4',

        /**
         * Default font family
         */
        'default_font' => 'serif',

        /**
         * Margin in points
         */
        'margin_left' => 30,
        'margin_right' => 30,
        'margin_top' => 30,
        'margin_bottom' => 30,

        /**
         * Image DPI setting
         */
        'dpi' => 96,

        /**
         * Enable inline PHP
         */
        'enable_php' => false,

        /**
         * Enable inline Javascript
         */
        'enable_javascript' => true,

        /**
         * Enable remote file access
         */
        'enable_remote' => true,

        /**
         * A ratio applied to the fonts. Set to 1 for normal, below 1 to make smaller.
         */
        'font_height_ratio' => 1.1,

        /**
         * Use the HTML5 Lib parser
         */
        'enable_html5_parser' => true,
    ],
];
