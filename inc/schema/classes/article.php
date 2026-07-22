<?php

class Theme_Article
{

    public static function build(): array
    {

        if(!is_single())
            return [];

        return [

            "@type"=>"Article",

            "@id"=>get_permalink().'#article',

            "headline"=>get_the_title(),

            "author"=>[
                "@id"=>home_url('/#person')
            ],

            "publisher"=>[
                "@id"=>home_url('/#organization')
            ],

            "datePublished"=>get_the_date('c'),

            "dateModified"=>get_the_modified_date('c')

        ];

    }

}