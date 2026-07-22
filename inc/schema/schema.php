<?php
/**
 * Schema Controller
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_head', 'theme_schema_output', 20);

function theme_schema_output(): void
{
    $graph = new Theme_Schema_Graph();
    /* Global */

    $graph->add(
        Theme_Logo::build()
    );

    $graph->add(
        Theme_Organisation::build()
    );

    $graph->add(
        Theme_Website::build()
    );

    $graph->add(
        Theme_WebPage::build()
    );

    /*$graph->add(
        Theme_Schema_LocalBusiness::build()
    );

    Standard Pages
    if (is_page() && !is_front_page()) {

        $graph->add(
            Theme_Service::build()
        );

    }

    Article
    if (is_single()) {

        $graph->add(
            Theme_Article::build()
        );

    }*/

    /*Optional Components*/

    $graph->add(
        Theme_FAQ::build()
    );
    
    /*if (Theme_Schema_Helpers::hasReviews()) {

        $graph->add(
            Theme_Review::build()
        );

    }*/

    $graph->output();
}