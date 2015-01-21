<?php

return [
    /**
     * Paths
     */
    'paths' => [
        /**
         * Where to look for stylesheet files when running the inliner
         */
        'stylesheets' => [
            base_path('resources/css/mail')
        ]
    ],

    /**
     * Some options for the inliner
     */
    'options' => [
        /**
         * Remove IDs and classes
         */
        'cleanup' => true,

        /**
         * Use the style block in the HTML
         */
        'use_inline_styles_block' => false,

        /**
         * Whether or not we should strip the original style tags
         * inside the HTML
         */
        'strip_original_tags' => false,

        /**
         * Exclude media queries from being inlined
         */
        'exclude_media_queries' => false
    ]
];
