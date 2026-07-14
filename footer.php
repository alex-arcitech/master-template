<?php
$email_svg = get_stylesheet_directory() . '/assets/svgs/mail.svg';
$phone_svg = get_stylesheet_directory() . '/assets/svgs/phone.svg';
$address_svg = get_stylesheet_directory() . '/assets/svgs/map.svg';

$email = (string) get_field('email', 'option');
$phone = (string) get_field('phone', 'option');
$address_raw = (string) get_field('address', 'option');
$address = wp_strip_all_tags($address_raw);
$maps_url = (string) get_field('maps_url', 'option');
$logo = get_field('footer_logo', 'option');
$site_title = get_bloginfo('name');

$logo_src = '';
$logo_alt = get_bloginfo('name');
if (is_array($logo) && isset($logo['ID'])) {
    $logo_src = (string) wp_get_attachment_image_url($logo['ID'], 'full');
    $logo_alt_candidate = (string) get_post_meta($logo['ID'], '_wp_attachment_image_alt', true);
    if ($logo_alt_candidate !== '') {
        $logo_alt = $logo_alt_candidate;
    }
}

$columns_raw = get_field('footer_columns', 'option') ?: [];
$columns = [];
foreach ($columns_raw as $col) {
    $col_title = isset($col['title']) ? (string) $col['title'] : '';
    $col_links = [];
    if (!empty($col['links']) && is_array($col['links'])) {
        foreach ($col['links'] as $ln) {
            $btn = isset($ln['button']) && is_array($ln['button']) ? $ln['button'] : null;
            $btn_title = $btn && isset($btn['title']) ? (string) $btn['title'] : '';
            $btn_url = $btn && isset($btn['url']) ? (string) $btn['url'] : '';
            $btn_target = $btn && isset($btn['target']) ? (string) $btn['target'] : '';
            if ($btn_title !== '' && $btn_url !== '') {
                $col_links[] = ['title' => $btn_title, 'url' => $btn_url, 'target' => $btn_target];
            }
        }
    }
    $columns[] = ['title' => $col_title, 'links' => $col_links];
}

$email_href = $email !== '' ? ('mailto:' . $email) : '';
$phone_href = $phone !== '' ? ('tel:' . $phone) : '';
$address_href = $maps_url !== '' ? $maps_url : '';
?>

<footer class="bg-theme text-white section">
    <div class="container">
        <div class="grid gap-8 md:gap-32 grid-cols-1 md:grid-cols-2 ">
            <div class="flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <div class="text-accent w-8">
                        <?php include $email_svg; ?>
                    </div>
                    <p>Email: <a href="<?php echo esc_attr($email_href); ?>"><?php echo esc_html($email); ?></a></p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-accent w-8">
                        <?php include $phone_svg; ?>
                    </div>
                    <p>Telephone: <a href="<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone); ?></a></p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-accent w-8">
                        <?php include $address_svg; ?>
                    </div>
                    <p>Address: <a href="<?php echo esc_url($address_href); ?>"><?php echo esc_html($address); ?></a>
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-4">
                <?php if (!empty($columns)): ?>
                    <?php foreach ($columns as $col): ?>
                        <div>
                            <?php if (!empty($col['title'])): ?>
                                <h6><?php echo esc_html($col['title']); ?></h6>
                            <?php endif; ?>
                            <div class="flex flex-col gap-2">
                                <?php if (!empty($col['links'])): ?>
                                    <?php foreach ($col['links'] as $lnk): ?>
                                        <a href="<?php echo esc_url($lnk['url']); ?>" <?php echo $lnk['target'] ? ' target="' . esc_attr($lnk['target']) . '"' : ''; ?>><?php echo esc_html($lnk['title']); ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="w-full flex flex-col-reverse md:flex-row justify-between mt-16 md:mt-32 gap-8">
            <p class="text-sm">Copyright <?php echo esc_html($site_title); ?> <?php echo date('Y'); ?>. All rights reserved. Built on <a
                    href="https://arcitech.co" class="font-light text-sm" target="_blank"
                    rel="noopener noreferrer">arcitech</a>.</p>
            <img class="w-48" src="<?php echo esc_url($logo_src); ?>" alt="<?php echo esc_attr($logo_alt); ?>">
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>