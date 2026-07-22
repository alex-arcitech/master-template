<?php

class Theme_WebPage
{

    public static function build(): array
    {

        return [

            "@type"=>"WebPage",

            "@id"=>get_permalink()."#webpage",

            "url"=>get_permalink(),

            "name"=>get_the_title(),

            "isPartOf"=>[
                "@id"=>home_url('/#website')
            ]

        ];

    }

}