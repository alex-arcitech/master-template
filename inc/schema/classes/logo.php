<?php

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Logo
{
    public static function build(): array
    {
        $logo = get_field('field_header_logo', 'option');

        if (!$logo) {
            return [];
        }

        // Image ID
        if (is_numeric($logo)) {

            $image = wp_get_attachment_image_src($logo, 'full');

            if (!$image) {
                return [];
            }

            return [

                "@type" => "ImageObject",

                "@id" => home_url('/#logo'),

                "url" => $image[0],

                "contentUrl" => $image[0],

                "caption" => get_bloginfo('name'),

                "inLanguage" => get_bloginfo('language'),

                "width" => (string) $image[1],

                "height" => (string) $image[2]

            ];
        }

        // Image Array
        if (is_array($logo)) {

            return [

                "@type" => "ImageObject",

                "@id" => home_url('/#logo'),

                "url" => $logo['url'],

                "contentUrl" => $logo['url'],

                "caption" => !empty($logo['alt'])
                    ? $logo['alt']
                    : get_bloginfo('name'),

                "inLanguage" => get_bloginfo('language'),

                "width" => (string) $logo['width'],

                "height" => (string) $logo['height']

            ];
        }

        // URL
        return [

            "@type" => "ImageObject",

            "@id" => home_url('/#logo'),

            "url" => $logo,

            "contentUrl" => $logo,

            "caption" => get_bloginfo('name'),

            "inLanguage" => get_bloginfo('language')

        ];
    }
}