<?php

class Theme_Website
{

    public static function build(): array
    {

        return [

            "@type"=>"WebSite",

            "@id"=>home_url('/#website'),

            "url"=>home_url(),

            "name"=>get_bloginfo('name'),

            "publisher"=>[
                "@id"=>home_url('/#organization')
            ]

        ];

    }

}