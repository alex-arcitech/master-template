<?php

class Theme_Service
{

    public static function build(): array
    {

        if(!is_page())
            return [];

        return [

            "@type"=>"Service",

            "@id"=>get_permalink().'#service',

            "name"=>get_the_title(),

            "description"=>get_the_excerpt(),

            "provider"=>[
                "@id"=>home_url('/#organization')
            ]

        ];

    }

}