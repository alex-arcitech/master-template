<?php
$option_1_title = (string) get_field('option_1_title');
$option_2_title = (string) get_field('option_2_title');

$rows_raw = get_field('rows');
$rows_raw = is_array($rows_raw) ? $rows_raw : [];

$impact_map = [
    'positive' => [
        'icon_color_class' => 'text-[#6CBA5E]',
    ],
    'negative' => [
        'icon_color_class' => 'text-red-500',
    ],
];

$rows = [];
foreach ($rows_raw as $row) {
    $option_1_impact_key = isset($row['option_1_impact']) ? (string) $row['option_1_impact'] : 'positive';
    $option_2_impact_key = isset($row['option_2_impact']) ? (string) $row['option_2_impact'] : 'positive';

    $option_1_impact = isset($impact_map[$option_1_impact_key]) ? $impact_map[$option_1_impact_key] : $impact_map['positive'];
    $option_2_impact = isset($impact_map[$option_2_impact_key]) ? $impact_map[$option_2_impact_key] : $impact_map['positive'];

    $rows[] = [
        'comparing' => isset($row['comparing']) ? (string) $row['comparing'] : '',
        'option_1_value' => isset($row['option_1_value']) ? (string) $row['option_1_value'] : '',
        'option_1_impact_key' => $option_1_impact_key,
        'option_1_impact_icon_color_class' => $option_1_impact['icon_color_class'],
        'option_2_value' => isset($row['option_2_value']) ? (string) $row['option_2_value'] : '',
        'option_2_impact_key' => $option_2_impact_key,
        'option_2_impact_icon_color_class' => $option_2_impact['icon_color_class'],
    ];
}

$row_count = count($rows);
$check_svg = get_stylesheet_directory() . '/assets/svgs/check.svg';
$cross_svg = get_stylesheet_directory() . '/assets/svgs/cross.svg';
?>

<section class="section my-12">
    <div class="container content">
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-0 rounded-lg overflow-hidden">
                <thead>
                    <tr>
                        <th scope="col" class="bg-[#FBFBFB] p-4 text-left font-semibold"></th>
                        <th scope="col" class="bg-[#FBFBFB] p-4 text-left font-bold text-xl pt-8 pb-6"><?php echo esc_html($option_1_title); ?></th>
                        <th scope="col" class="bg-[#F1F5FF] p-4 text-left font-bold text-theme text-xl pt-8 pb-6"><?php echo esc_html($option_2_title); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $index => $row): ?>
                        <?php $row_border_class = $index < $row_count - 1 ? ' border-b border-slate-300' : ''; ?>
                        <?php $last_row_padding_class = $index === $row_count - 1 ? ' pb-8' : ''; ?>
                        <?php $option_1_icon_svg = $row['option_1_impact_key'] === 'negative' ? $cross_svg : $check_svg; ?>
                        <?php $option_2_icon_svg = $row['option_2_impact_key'] === 'negative' ? $cross_svg : $check_svg; ?>
                        <tr>
                            <th scope="row" class="bg-[#FBFBFB] p-4 pr-2 pl-6 text-left align-top font-semibold<?php echo esc_attr($last_row_padding_class); ?>">
                                <?php echo esc_html($row['comparing']); ?>
                            </th>
                            <td class="bg-[#FBFBFB] p-4 align-top<?php echo esc_attr($row_border_class . $last_row_padding_class); ?>">
                                <span class="inline-flex items-start gap-2 text-sm font-semibold tracking-wide">
                                    <span class="w-3 shrink-0 mt-1 <?php echo esc_attr($row['option_1_impact_icon_color_class']); ?>" aria-hidden="true">
                                        <?php if (file_exists($option_1_icon_svg)): ?>
                                            <?php include $option_1_icon_svg; ?>
                                        <?php endif; ?>
                                    </span>
                                    <span class="font-normal"><?php echo esc_html($row['option_1_value']); ?></span>
                                </span>
                            </td>
                            <td class="bg-[#F1F5FF] p-4 align-top relative<?php echo esc_attr($last_row_padding_class); ?>">
                                <?php if ($index < $row_count - 1): ?>
                                    <span class="pointer-events-none absolute bottom-0 left-0 right-4 border-b border-slate-300" aria-hidden="true"></span>
                                <?php endif; ?>
                                <span class="inline-flex items-start gap-2 text-sm font-semibold tracking-wide">
                                    <span class="w-3 shrink-0 mt-1 <?php echo esc_attr($row['option_2_impact_icon_color_class']); ?>" aria-hidden="true">
                                        <?php if (file_exists($option_2_icon_svg)): ?>
                                            <?php include $option_2_icon_svg; ?>
                                        <?php endif; ?>
                                    </span>
                                    <span class="font-normal"><?php echo esc_html($row['option_2_value']); ?></span>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>