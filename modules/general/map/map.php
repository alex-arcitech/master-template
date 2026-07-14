<?php

$lat_raw = get_field('latitude');
$lng_raw = get_field('longitude');

$lat = is_numeric($lat_raw) ? (float) $lat_raw : 51.6383453;
$lng = is_numeric($lng_raw) ? (float) $lng_raw : -0.0756483;


$zoom = 13;

$tailwind_config_path = get_stylesheet_directory() . '/tailwind.config.js';
$tailwind_config = is_readable($tailwind_config_path) ? (string) file_get_contents($tailwind_config_path) : '';

$extract_tailwind_color = static function (string $config, string $name, string $fallback): string {
    $pattern = '/\\b' . preg_quote($name, '/') . '\\s*:\\s*["\'](#[0-9A-Fa-f]{3,8})["\']/';
    if (preg_match($pattern, $config, $matches) === 1) {
        return $matches[1];
    }

    return $fallback;
};

/* Map theme color from tailwind themelight */
$map_color = $extract_tailwind_color($tailwind_config, 'themelight', '#F1F6F9');

/* Pin color from tailwind accent */
$pin_color = $extract_tailwind_color($tailwind_config, 'accent', '#F0544F');

$location_svg_path = get_stylesheet_directory() . '/assets/svgs/location.svg';
$location_svg_markup = is_readable($location_svg_path) ? (string) file_get_contents($location_svg_path) : '';
$location_svg_markup = $location_svg_markup !== '' ? str_replace('currentColor', $pin_color, $location_svg_markup) : '';
$marker_icon_url = $location_svg_markup !== '' ? 'data:image/svg+xml;base64,' . base64_encode($location_svg_markup) : '';


?>

<div class="relative overflow-hidden">
    <div id="custom-map" class="w-full h-[270px] md:h-[500px]"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-black/10 to-transparent"></div>
</div>

<script>
    function initCustomMap() {

        const location = {
            lat: <?php echo $lat; ?>,
            lng: <?php echo $lng; ?>
        };

        const map = new google.maps.Map(document.getElementById("custom-map"), {
            zoom: <?php echo $zoom; ?>,
            center: location,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                {
                    featureType: "all",
                    elementType: "all",
                    stylers: [
                        { saturation: -50 } // -100 is completely gray, 0 is normal
                    ]
                },
                {
                    featureType: "poi",
                    stylers: [{ visibility: "off" }]
                },
                {
                    featureType: "landscape",
                    elementType: "geometry",
                    stylers: [{ color: "<?php echo esc_js($map_color); ?>" }]
                },
            ]
        });

        const marker = new google.maps.Marker({
            position: location,
            map: map,
            icon: {
                url:
                    "<?php echo esc_js($marker_icon_url); ?>",
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 32)
            }
        });

    }
</script>