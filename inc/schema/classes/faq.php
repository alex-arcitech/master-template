<?php

class Theme_FAQ
{
    public static function build(): array
    {
        $questions = [];

        $blocks = parse_blocks(get_the_content());

        foreach ($blocks as $block) {

            if (($block['blockName'] ?? '') !== 'acf/accordion') {
                continue;
            }

            $data = $block['attrs']['data'] ?? [];

            if (($data['content_purpose'] ?? '') !== 'faq') {
                continue;
            }

            $count = intval($data['items'] ?? 0);

            for ($i = 0; $i < $count; $i++) {

                $question = trim($data["items_{$i}_title"] ?? '');
                $answer   = trim($data["items_{$i}_text"] ?? '');

                if (!$question || !$answer) {
                    continue;
                }

                $questions[] = [

                    "@type" => "Question",

                    "name" => wp_strip_all_tags($question),

                    "acceptedAnswer" => [

                        "@type" => "Answer",

                        "text" => wp_strip_all_tags($answer)

                    ]

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