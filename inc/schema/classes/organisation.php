<?php

class Theme_Organisation
{
    public static function build(): array
    {
        return [

            "@type" => "Organization",

            "@id" => home_url('/#organization'),

            "name" => get_field('field_company_name','option'),

            "url" => home_url(),

               "logo" => [

                "@id" => home_url('/#logo')

               ],

            "telephone" => get_field('field_contact_phone','option'),

            "email" => get_field('field_contact_email','option'),

            "address" => Theme_Schema_Helpers::postalAddress()

        ];
    }
}