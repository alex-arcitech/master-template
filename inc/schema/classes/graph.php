<?php
/**
 * JSON-LD Graph Builder
 */

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Schema_Graph
{
    /**
     * Graph Entities
     */
    protected array $entities = [];

    /**
     * Add Schema
     */
    public function add(?array $entity): void
    {
        if (empty($entity)) {
            return;
        }

        $this->entities[] = $entity;
    }

    /**
     * Output Graph
     */
    public function output(): void
    {
        if (empty($this->entities)) {
            return;
        }

        echo PHP_EOL;
        echo '<script type="application/ld+json">';
        echo PHP_EOL;

        echo wp_json_encode(
            [
                '@context' => 'https://schema.org',
                '@graph'   => $this->entities
            ],
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE |
            JSON_PRETTY_PRINT
        );

        echo PHP_EOL;
        echo '</script>';
        echo PHP_EOL;
    }
}