<?php
/*
 * Template Name: SEO Page
 * Template Post Type: page
 */

$current_page = get_queried_object();
$current_page_id = ($current_page instanceof WP_Post) ? (int) $current_page->ID : (int) get_the_ID();
$current_page_title = $current_page_id ? (string) get_the_title($current_page_id) : '';
$current_page_title_display = $current_page_title !== '' ? $current_page_title : 'Untitled';

$cluster_raw = $current_page_id ? get_field('cluster', $current_page_id) : '';
$cluster = trim((string) $cluster_raw);
$cluster_title = $cluster !== '' ? $cluster : $current_page_title;

$cluster_page_ids = [];
if ($cluster !== '') {
    $cluster_page_ids = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => 'position_in_cluster',
        'orderby' => [
            'meta_value_num' => 'ASC',
            'title' => 'ASC',
        ],
        'meta_query' => [
            'relation' => 'AND',
            [
                'relation' => 'OR',
                [
                    'key' => '_wp_page_template',
                    'value' => 'page-seo.php',
                    'compare' => '=',
                ],
                [
                    'key' => '_wp_page_template',
                    'value' => 'seo-page.php',
                    'compare' => '=',
                ],
            ],
            [
                'key' => 'cluster',
                'value' => $cluster,
                'compare' => '=',
            ],
        ],
    ]);
}

$cluster_page_ids = is_array($cluster_page_ids) ? $cluster_page_ids : [];

if (empty($cluster_page_ids) && $current_page_id) {
    $cluster_page_ids = [$current_page_id];
}

$cluster_pages = [];
foreach ($cluster_page_ids as $page_id) {
    $page_id = (int) $page_id;
    if (!$page_id) {
        continue;
    }

    $page_url = (string) get_permalink($page_id);
    $is_current = $current_page_id === $page_id;

    $cluster_pages[] = [
        'id' => $page_id,
        'title' => (string) get_the_title($page_id),
        'url' => $page_url !== '' ? $page_url : '#',
        'is_current' => $is_current,
        'link_class' => 'seo-page-cluster-nav-link' . ($is_current ? ' is-active font-bold' : ''),
        'aria_current' => $is_current ? 'page' : '',
    ];
}

$allowed_blocks = function_exists('allowed_blocks') ? allowed_blocks(get_post()) : [];

$post_obj = get_post();
$rendered = false;

$content_html = '';
if ($post_obj) {
    ob_start();

    $blocks = parse_blocks((string) $post_obj->post_content);

    foreach ($blocks as $block) {
        $name = isset($block['blockName']) ? (string) $block['blockName'] : '';
        if ($name === '') {
            continue;
        }

        if (!empty($allowed_blocks) && !in_array($name, $allowed_blocks, true)) {
            continue;
        }

        echo render_block($block);
        $rendered = true;
    }

    if (!$rendered && have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }

    $content_html = (string) ob_get_clean();
}

$toc_items = [];
if ($content_html !== '') {
    $slug_counts = [];
    $slugify = static function (string $value): string {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9\s-]/', '', $value) ?? '';
        $value = preg_replace('/\s+/', '-', $value) ?? '';
        return preg_replace('/-+/', '-', $value) ?? '';
    };

    $previous_use_internal_errors = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?><div id="seo-page-content-root">' . $content_html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    $all_heading_nodes = $xpath->query('//*[@id="seo-page-content-root"]//h1 | //*[@id="seo-page-content-root"]//h2 | //*[@id="seo-page-content-root"]//h3 | //*[@id="seo-page-content-root"]//h4 | //*[@id="seo-page-content-root"]//h5 | //*[@id="seo-page-content-root"]//h6');
    if ($all_heading_nodes instanceof DOMNodeList) {
        foreach ($all_heading_nodes as $all_heading_node) {
            if (!($all_heading_node instanceof DOMElement)) {
                continue;
            }

            $existing_style = (string) $all_heading_node->getAttribute('style');
            $style_without_weight = preg_replace('/(?:^|;)\s*font-weight\s*:\s*[^;]+;?/i', ';', $existing_style) ?? '';
            $style_without_weight = trim((string) preg_replace('/\s*;\s*/', '; ', $style_without_weight), " ;");
            $normalized_style = $style_without_weight !== '' ? $style_without_weight . '; ' : '';
            $all_heading_node->setAttribute('style', $normalized_style . 'font-weight: 700 !important;');
        }
    }

    $heading_nodes = $xpath->query('//*[@id="seo-page-content-root"]//h2');

    if ($heading_nodes instanceof DOMNodeList) {
        foreach ($heading_nodes as $index => $heading_node) {
            if (!($heading_node instanceof DOMElement)) {
                continue;
            }

            $label = trim($heading_node->textContent);
            if ($label === '') {
                continue;
            }

            $base_slug = $slugify($label);
            if ($base_slug === '') {
                $base_slug = 'section-' . ($index + 1);
            }

            $slug_counts[$base_slug] = ($slug_counts[$base_slug] ?? 0) + 1;
            $heading_id = $slug_counts[$base_slug] > 1 ? $base_slug . '-' . $slug_counts[$base_slug] : $base_slug;

            if (trim((string) $heading_node->getAttribute('id')) === '') {
                $heading_node->setAttribute('id', $heading_id);
            } else {
                $heading_id = trim((string) $heading_node->getAttribute('id'));
            }

            if ($index === 0) {
                $existing_class = trim((string) $heading_node->getAttribute('class'));
                $first_h2_class = 'seo-page-first-h2';
                $heading_node->setAttribute(
                    'class',
                    $existing_class !== '' ? $existing_class . ' ' . $first_h2_class : $first_h2_class
                );
            }

            $toc_items[] = [
                'id' => $heading_id,
                'label' => $label,
                'level' => strtolower($heading_node->tagName),
            ];
        }
    }

    $content_root = $xpath->query('//*[@id="seo-page-content-root"]')->item(0);
    $content_html = '';
    if ($content_root instanceof DOMNode) {
        foreach ($content_root->childNodes as $child_node) {
            $content_html .= $dom->saveHTML($child_node);
        }
    }

    libxml_clear_errors();
    libxml_use_internal_errors($previous_use_internal_errors);
}

add_action('wp_head', static function () {
    echo '<style>.wp-block-paragraph a, .accordion-block a { font-style: italic; font-weight: 600; }</style>';
});

get_header();
?>
<main id="site-content" role="main"
    class="site-main seo-page-template arcitech-content relative mt-[var(--site-header-height)] font-normal [&_.font-thin]:font-normal [&_.font-extralight]:font-normal [&_.font-light]:font-normal [&_.font-\[300\]]:font-normal [&_.container]:mx-0 [&_.container]:px-0 [&_.container]:max-w-none [&_.container.content]:mx-0 [&_.container.content]:px-0 [&_.container.content]:max-w-none [&_.section]:py-0">
    <div
        class="seo-page-layout grid w-full max-w-[1512px] mx-auto grid-cols-1 lg:grid-cols-[240px_minmax(0,1fr)] min-[1150px]:grid-cols-[300px_minmax(0,1fr)_270px] items-start">

        <aside
            class="left-panel hidden lg:block sticky top-[var(--site-header-height)] h-[calc(100vh-var(--site-header-height))] w-[240px] min-[1150px]:w-[300px] text-sm min-[1150px]:text-base border-r-gray-200 border-r-solid border-r p-6 overflow-y-auto col-start-1 row-start-1">
            <div class="title">
                <p class="text-sm">Resources</p>
                <p class="text-sm min-[1150px]:text-lg font-bold pb-2 border-b border-b-gray-200">
                    <?php echo esc_html($cluster_title); ?>
                </p>
            </div>
            <div class='pt-2'>
                <nav class="seo-page-cluster-nav" aria-label="Cluster navigation">
                    <ul class="seo-page-cluster-nav-list">
                        <?php foreach ($cluster_pages as $item): ?>
                            <li
                                class="py-1 px-2 seo-page-cluster-nav-item my-2 <?php echo $item['is_current'] ? ' bg-theme text-white rounded-md' : ''; ?>">
                                <a class="<?php echo esc_attr($item['link_class']); ?> block"
                                    href="<?php echo esc_url($item['url']); ?>" <?php echo $item['aria_current'] !== '' ? 'aria-current="page"' : ''; ?>>
                                    <?php echo esc_html($item['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <aside
            class="right-panel hidden min-[1150px]:block sticky top-[var(--site-header-height)] h-[calc(100vh-var(--site-header-height))] w-[270px] border-l border-l-gray-200 p-6 overflow-y-auto min-[1150px]:col-start-3 min-[1150px]:row-start-1">
            <p class="font-medium pt-6 pb-2 border-b border-b-gray-200">On this page</p>
            <div class="pt-4">
                <nav aria-label="On this page navigation">
                    <ul>
                        <?php foreach ($toc_items as $toc_item): ?>
                            <li class="border-l-2 border-l-gray-200 py-2 pl-4">
                                <a class="h-full w-full text-sm flex items-center text-themedark transition-colors hover:text-theme"
                                    href="#<?php echo esc_attr($toc_item['id']); ?>">
                                    <?php echo esc_html($toc_item['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>

        </aside>

        <div class="seo-page-content min-w-0 col-start-1 lg:col-start-2 row-start-1">
            <div class="top-panel p-10 flex flex-col gap-4 border-b border-b-gray-200">
                <div class="text-sm">
                    <span>Resources / </span>
                    <span><?php echo esc_html($cluster_title); ?> / </span>
                    <span><?php echo esc_html($current_page_title_display); ?></span>
                </div>
                <h1 class="text-theme"><?php echo esc_html($current_page_title_display); ?></h1>
            </div>
            <div class="p-10">
                <?php echo $content_html; ?>
            </div>

        </div>
    </div>
</main>

<?php
get_footer();
