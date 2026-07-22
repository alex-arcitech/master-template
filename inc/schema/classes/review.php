<?php

function theme_schema_review()
{

    $reviews=[];

    while(have_rows('testimonials')){

        the_row();

        $reviews[]=[

            "@type"=>"Review",

            "reviewBody"=>get_sub_field('review'),

            "author"=>[
                "@type"=>"Person",
                "name"=>get_sub_field('name')
            ]

        ];

    }

    $schema=[

        "@context"=>"https://schema.org",

        "@graph"=>$reviews

    ];

    theme_output_jsonld($schema);

}