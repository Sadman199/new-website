<?php

namespace App\Support;

class CmsSectionRegistry
{
    public const TYPES = [
        'hero',
        'text_content',
        'image_text',
        'cards',
        'statistics',
        'faq',
        'timeline',
        'team_members',
        'table',
        'cta',
        'contact_form',
        'glossary',
    ];

    public const TEMPLATES = [
        'default' => 'Default',
        'landing' => 'Landing (full-width hero)',
        'legal' => 'Legal (narrow prose)',
    ];

    public static function labels(): array
    {
        return [
            'hero' => 'Hero',
            'text_content' => 'Text Content',
            'image_text' => 'Image + Text',
            'cards' => 'Cards Grid',
            'statistics' => 'Statistics',
            'faq' => 'FAQ',
            'timeline' => 'Timeline',
            'team_members' => 'Team Members',
            'table' => 'Table',
            'cta' => 'Call to Action',
            'contact_form' => 'Contact Form',
            'glossary' => 'Glossary Listing',
        ];
    }

    public static function label(string $type): string
    {
        return self::labels()[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    /** Admin UI: grouped section palette with icons and helper text. */
    public static function adminCatalog(): array
    {
        return [
            'Page header' => [
                'hero' => [
                    'icon' => 'fa-image',
                    'desc' => 'Dark hero with title, metrics, and optional CTAs — matches site page headers.',
                ],
            ],
            'Content blocks' => [
                'text_content' => [
                    'icon' => 'fa-align-left',
                    'desc' => 'Heading plus rich text body.',
                ],
                'image_text' => [
                    'icon' => 'fa-columns',
                    'desc' => 'Side-by-side image and copy.',
                ],
                'cards' => [
                    'icon' => 'fa-th-large',
                    'desc' => 'Grid of feature or benefit cards.',
                ],
                'statistics' => [
                    'icon' => 'fa-chart-bar',
                    'desc' => 'Metric row using the site hero-metrics style.',
                ],
                'table' => [
                    'icon' => 'fa-table',
                    'desc' => 'Data table with headers and rows.',
                ],
                'glossary' => [
                    'icon' => 'fa-book',
                    'desc' => 'Alphabetical term definitions list.',
                ],
            ],
            'Story & people' => [
                'timeline' => [
                    'icon' => 'fa-stream',
                    'desc' => 'Chronological milestones.',
                ],
                'team_members' => [
                    'icon' => 'fa-users',
                    'desc' => 'Team grid with photo, role, and bio.',
                ],
                'faq' => [
                    'icon' => 'fa-question-circle',
                    'desc' => 'Expandable question and answer list.',
                ],
            ],
            'Conversion' => [
                'cta' => [
                    'icon' => 'fa-bullhorn',
                    'desc' => 'Call-to-action banner with button.',
                ],
                'contact_form' => [
                    'icon' => 'fa-envelope',
                    'desc' => 'Embedded contact form (uses site handler).',
                ],
            ],
        ];
    }

    public static function adminCatalogFlat(): array
    {
        $flat = [];
        foreach (self::adminCatalog() as $group => $items) {
            foreach ($items as $type => $meta) {
                $flat[$type] = array_merge($meta, [
                    'label' => self::label($type),
                    'group' => $group,
                ]);
            }
        }

        return $flat;
    }

    public static function defaults(string $type): array
    {
        return match ($type) {
            'hero' => [
                'eyebrow' => '',
                'headline' => '',
                'subheadline' => '',
                'background_style' => 'dark',
                'cta_label' => '',
                'cta_url' => '',
                'secondary_cta_label' => '',
                'secondary_cta_url' => '',
                'metrics' => [],
            ],
            'text_content' => [
                'heading' => '',
                'body' => '',
                'align' => 'left',
            ],
            'image_text' => [
                'heading' => '',
                'body' => '',
                'image' => '',
                'image_alt' => '',
                'image_position' => 'right',
            ],
            'cards' => [
                'heading' => '',
                'subheading' => '',
                'columns' => 3,
                'items' => [],
            ],
            'statistics' => [
                'heading' => '',
                'items' => [],
            ],
            'faq' => [
                'heading' => '',
                'items' => [],
            ],
            'timeline' => [
                'heading' => '',
                'items' => [],
            ],
            'team_members' => [
                'heading' => '',
                'subheading' => '',
                'items' => [],
            ],
            'table' => [
                'heading' => '',
                'caption' => '',
                'headers' => [],
                'rows' => [],
            ],
            'cta' => [
                'heading' => '',
                'text' => '',
                'button_label' => '',
                'button_url' => '',
                'style' => 'primary',
            ],
            'contact_form' => [
                'heading' => 'Contact Us',
                'subheading' => '',
                'show_info_cards' => true,
            ],
            'glossary' => [
                'heading' => '',
                'intro' => '',
                'items' => [],
            ],
            default => [],
        };
    }

    public static function isValidType(string $type): bool
    {
        return in_array($type, self::TYPES, true);
    }

    public static function isValidTemplate(string $template): bool
    {
        return array_key_exists($template, self::TEMPLATES);
    }

    /** Slugs reserved by existing application routes. */
    public static function reservedSlugs(): array
    {
        return [
            'admin', 'login', 'register', 'logout', 'profile', 'auth',
            'about', 'about-us', 'contact', 'authors', 'blog', 'news',
            'brokers', 'best-brokers', 'broker-reviews', 'best-regulated-brokers',
            'regulated-brokers', 'non-regulated-brokers', 'scam-brokers', 'awards', 'insights',
            'forex-calculator', 'trading-tools', 'our-methodology',
            'terms-and-conditions', 'privacy-policy', 'disclaimer',
            'broker-promos', 'forex-deposit-bonus', 'forex-no-deposit-bonus',
            'forex-live-contest', 'forex-demo-contest', 'forex-cashback-rebate',
            'crypto-bonus-promotion', 'bonuses', 'subscribe', 'verify-subscription',
            'health', 'broker-live-search', 'broker-scam-checker', 'video-gallery', 'search',
            'sitemap.xml', 'robots.txt', 'compare',
            'archive', 'tag', 'api', 'storage', 'uploads', 'css', 'js', 'dist',
        ];
    }
}
