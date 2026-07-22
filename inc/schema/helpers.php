<?php
/**
 * Theme Schema Helpers
 *
 * Shared helper methods used across all schema classes.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Schema_Helpers
{
    /**
     * Organisation ID
     */
    public static function organisationId(): string
    {
        return home_url('/#organization');
    }

    /**
     * Website ID
     */
    public static function websiteId(): string
    {
        return home_url('/#website');
    }

    /**
     * WebPage ID
     */
    public static function webpageId(): string
    {
        return get_permalink() . '#webpage';
    }

    /**
     * Current Page URL
     */
    public static function url(): string
    {
        return get_permalink();
    }

    /**
     * Current Page Title
     */
    public static function title(): string
    {
        return wp_strip_all_tags(get_the_title());
    }

    /**
     * Current Page Description
     */
    public static function description(): string
    {
        if (has_excerpt()) {
            return wp_strip_all_tags(get_the_excerpt());
        }

        return wp_trim_words(
            wp_strip_all_tags(get_the_content()),
            40
        );
    }

    /**
     * Featured Image
     */
    public static function image(): ?string
    {
        if (has_post_thumbnail()) {
            return get_the_post_thumbnail_url(null, 'full');
        }

        return null;
    }

    /**
     * Output ISO Date
     */
    public static function publishedDate(): string
    {
        return get_the_date(DATE_W3C);
    }

    public static function modifiedDate(): string
    {
        return get_the_modified_date(DATE_W3C);
    }

    /**
     * Check ACF Repeaters
     */
    public static function hasFaqs(): bool
    {
        $blocks = parse_blocks(get_the_content());
    
        foreach ($blocks as $block) {
        
            if (($block['blockName'] ?? '') !== 'acf/accordion') {
                continue;
            }
        
            $data = $block['attrs']['data'] ?? [];
        
            if (($data['content_purpose'] ?? '') === 'faq') {
                return true;
            }
        }
    
        return false;
    }
    public static function hasReviews(): bool
    {
        return function_exists('have_rows') && have_rows('testimonials');
    }

    /**
     * Company Information
     */

    public static function companyName(): ?string
    {
        return get_field('Company Name', 'option');
    }

    public static function companyPhone(): ?string
    {
        return get_field('phone', 'option');
    }

    public static function companyEmail(): ?string
    {
        return get_field('email', 'option');
    }

    public static function postalAddress(): array
    {
        $address = get_field('address', 'option');

        if (!$address) {
            return [];
        }

        $address = strip_tags($address);

        $lines = array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', $address))
        ));

        return [
            "@type" => "PostalAddress",
            "streetAddress" => $lines[0] ?? '',
            "addressLocality" => $lines[1] ?? '',
            "addressRegion" => $lines[2] ?? '',
            "postalCode" => $lines[3] ?? '',
            "addressCountry" => $lines[4] ?? '',
        ];
    }

}