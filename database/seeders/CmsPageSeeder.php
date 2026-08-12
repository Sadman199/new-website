<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Services\CmsPageService;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(CmsPageService::class);

        $pages = [
            [
                'title' => 'For Businesses',
                'slug' => 'for-businesses',
                'template' => 'landing',
                'meta_title' => 'For Businesses | BrokersCourt',
                'meta_description' => 'Partner with BrokersCourt to reach informed traders through independent broker reviews and editorial coverage.',
                'status' => 'published',
                'sections' => [
                    [
                        'section_type' => 'hero',
                        'section_data' => [
                            'eyebrow' => 'Partnerships',
                            'headline' => 'Grow with BrokersCourt',
                            'subheadline' => 'Reach traders who rely on independent research before choosing a broker.',
                            'background_style' => 'dark',
                            'cta_label' => 'Contact partnerships',
                            'cta_url' => '/contact',
                            'metrics' => [
                                ['label' => 'Monthly readers', 'value' => '250K+', 'tone' => 'highlight'],
                                ['label' => 'Broker profiles', 'value' => '180+', 'tone' => ''],
                                ['label' => 'Editorial reviews', 'value' => '500+', 'tone' => ''],
                            ],
                        ],
                    ],
                    [
                        'section_type' => 'cards',
                        'section_data' => [
                            'heading' => 'Why partner with us',
                            'columns' => 3,
                            'items' => [
                                ['title' => 'Trusted audience', 'text' => 'Traders who compare brokers before signing up.', 'icon' => '🎯', 'url' => ''],
                                ['title' => 'Editorial integrity', 'text' => 'Independent methodology with transparent scoring.', 'icon' => '⚖️', 'url' => '/our-methodology'],
                                ['title' => 'Flexible formats', 'text' => 'Profiles, guides, sponsored placements, and more.', 'icon' => '📣', 'url' => ''],
                            ],
                        ],
                    ],
                    [
                        'section_type' => 'statistics',
                        'section_data' => [
                            'heading' => 'Reach at a glance',
                            'items' => [
                                ['value' => '12+', 'label' => 'Markets covered'],
                                ['value' => '40+', 'label' => 'Contributors'],
                                ['value' => '98%', 'label' => 'Organic traffic'],
                            ],
                        ],
                    ],
                    [
                        'section_type' => 'cta',
                        'section_data' => [
                            'heading' => 'Ready to collaborate?',
                            'text' => 'Tell us about your brand and goals — our team will respond within two business days.',
                            'button_label' => 'Get in touch',
                            'button_url' => '/contact',
                            'style' => 'primary',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us-cms',
                'template' => 'default',
                'meta_title' => 'About Us | BrokersCourt',
                'meta_description' => 'Learn about BrokersCourt — independent forex broker reviews for traders worldwide.',
                'status' => 'draft',
                'sections' => [
                    [
                        'section_type' => 'hero',
                        'section_data' => [
                            'headline' => 'About BrokersCourt',
                            'subheadline' => 'Independent reviews. Transparent methodology. Trader-first editorial.',
                            'background_style' => 'dark',
                        ],
                    ],
                    [
                        'section_type' => 'text_content',
                        'section_data' => [
                            'heading' => 'Our story',
                            'body' => '<p>BrokersCourt helps traders compare brokers with clarity and confidence.</p>',
                        ],
                    ],
                    [
                        'section_type' => 'team_members',
                        'section_data' => [
                            'heading' => 'Meet the team',
                            'items' => [
                                ['name' => 'Editorial Lead', 'role' => 'Research', 'photo' => '', 'bio' => 'Oversees broker scoring and review quality.'],
                            ],
                        ],
                    ],
                    [
                        'section_type' => 'timeline',
                        'section_data' => [
                            'heading' => 'Milestones',
                            'items' => [
                                ['year' => '2020', 'title' => 'Launch', 'text' => 'BrokersCourt goes live with broker comparison tools.'],
                                ['year' => '2024', 'title' => 'Expansion', 'text' => 'Added scam alerts, promotions hub, and trading tools.'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Glossary',
                'slug' => 'glossary',
                'template' => 'legal',
                'meta_title' => 'Trading Glossary | BrokersCourt',
                'meta_description' => 'Definitions of common forex and CFD trading terms.',
                'status' => 'published',
                'sections' => [
                    [
                        'section_type' => 'hero',
                        'section_data' => [
                            'headline' => 'Trading Glossary',
                            'subheadline' => 'Quick definitions for terms you will see across our reviews and guides.',
                            'background_style' => 'light',
                        ],
                    ],
                    [
                        'section_type' => 'glossary',
                        'section_data' => [
                            'intro' => 'Browse alphabetically or jump to a term using your browser search.',
                            'items' => [
                                ['term' => 'Spread', 'definition' => 'The difference between the bid and ask price of an instrument.'],
                                ['term' => 'Leverage', 'definition' => 'Borrowed capital that amplifies position size relative to account equity.'],
                                ['term' => 'ECN', 'definition' => 'Electronic Communication Network — a broker model that routes orders to liquidity providers.'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($pages as $payload) {
            $sections = $payload['sections'];
            unset($payload['sections']);

            if (CmsPage::where('slug', $payload['slug'])->exists()) {
                continue;
            }

            $service->savePage(new CmsPage(), $payload, $sections);
        }
    }
}
