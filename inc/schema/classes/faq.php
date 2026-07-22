<?php

class Theme_FAQ
{
    public static function build(): array
    {
        $questions = [];

        $blocks = parse_blocks(get_the_content());

        foreach ($blocks as $block) {
        
            $block_name = $block['blockName'] ?? '';
        
            if (!in_array($block_name, [
        'acf/accordion-feature',
        'acf/accordion-float-right',
        'acf/accordion-grid',
            ], true)) {
        continue;
            }
        
            $data = $block['attrs']['data'] ?? [];
        
            // Only include accordions marked as schema
            if (empty($data['is_schema'])) {
        continue;
            }
        
            // Determine the repeater field name
            $repeater = match ($block_name) {
            'acf/accordion-float-right' => 'accordion_items',
        default => 'faq_items',
            };
        
            $count = (int) ($data[$repeater] ?? 0);
        
            for ($i = 0; $i < $count; $i++) {
            
                $question = trim($data["{$repeater}_{$i}_title"] ?? '');
                $answer   = trim($data["{$repeater}_{$i}_text"] ?? '');
            
                if (!$question || !$answer) {
            continue;
                }
            
                $questions[] = [
                    "@type" => "Question",
                    "name" => wp_strip_all_tags($question),
                    "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => wp_strip_all_tags($answer),
                    ],
                ];
            }
        }

        if (empty($questions)) {
            return [];
        }

        return [

            "@type" => "FAQPage",

            "@id" => get_permalink() . "#faq",

            "mainEntity" => $questions

        ];
    }
}