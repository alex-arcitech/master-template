<?php

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Schema_LocalBusiness
{
    public static function build(): array
    {
        return [

            "@context" => "https://schema.org",

            "@type" => "LocalBusiness",

            "@id" => home_url('/#localbusiness'),

            "name" => Theme_Schema_Helpers::companyName(),

            "telephone" => Theme_Schema_Helpers::companyPhone(),

            "email" => Theme_Schema_Helpers::companyEmail(),

            "address" => Theme_Schema_Helpers::postalAddress(),

        ];
    }
}