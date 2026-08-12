<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Page;
use App\Support\SiteTheme;

class ContactIndexService
{
    public function resolveLanguageId(): int
    {
        $shortName = session()->get('session_short_name')
            ?? optional(Language::where('is_default', 'Yes')->first())->short_name
            ?? 'en';

        return (int) (optional(Language::where('short_name', $shortName)->first())->id ?? 1);
    }

    /** @return array<string, mixed> */
    public function buildIndex(int $languageId): array
    {
        $page = Page::where('language_id', $languageId)->first();

        return [
            'page' => $this->pageContent($page),
            'channels' => $this->channels(),
            'stats' => $this->stats(),
            'quick_links' => $this->quickLinks(),
        ];
    }

    /** @return array<string, string|null> */
    private function pageContent(?Page $page): array
    {
        return [
            'title' => $page?->contact_title ?: 'Contact Us',
            'detail' => $page?->contact_detail ?: 'Questions about broker reviews, partnerships, or editorial feedback? Send us a message and the BrokersCourt team will get back to you.',
            'map' => $page?->contact_map ?: null,
            'status' => $page?->contact_status ?: 'Show',
        ];
    }

    /** @return array<int, array<string, string|null>> */
    private function channels(): array
    {
        $phone = SiteTheme::contactPhone();

        return [
            [
                'key' => 'email',
                'label' => 'Email',
                'value' => 'info@brokerscourt.com',
                'href' => 'mailto:info@brokerscourt.com',
                'hint' => 'Best for detailed questions and document attachments.',
            ],
            [
                'key' => 'phone',
                'label' => 'Phone',
                'value' => $phone,
                'href' => 'tel:' . preg_replace('/\s+/', '', $phone),
                'hint' => 'Speak with our team during office hours.',
            ],
            [
                'key' => 'office',
                'label' => 'Office',
                'value' => 'Al Nahda 2, Dubai',
                'href' => null,
                'hint' => 'Editorial and business correspondence.',
            ],
            [
                'key' => 'hours',
                'label' => 'Hours',
                'value' => 'Mon–Fri, 9 AM – 5 PM EST',
                'href' => null,
                'hint' => 'We aim to reply within one business day.',
            ],
        ];
    }

    /** @return array<int, array<string, string>> */
    private function stats(): array
    {
        return [
            ['label' => 'Response time', 'value' => '~24h'],
            ['label' => 'Office', 'value' => 'Dubai'],
            ['label' => 'Support days', 'value' => 'Mon–Fri'],
            ['label' => 'Languages', 'value' => 'English'],
        ];
    }

    /** @return array<int, array<string, string>> */
    private function quickLinks(): array
    {
        return [
            [
                'label' => 'Scam broker checker',
                'desc' => 'Verify a broker before you deposit',
                'route' => 'broker.scam_checker',
                'icon' => 'fa-solid fa-shield-halved',
            ],
            [
                'label' => 'Regulated brokers',
                'desc' => 'Browse licensed & regulated brokers',
                'route' => 'regulated_brokers',
                'icon' => 'fa-solid fa-certificate',
            ],
            [
                'label' => 'Our editorial team',
                'desc' => 'Meet the writers behind our reviews',
                'route' => 'authors',
                'icon' => 'fa-solid fa-users',
            ],
            [
                'label' => 'Trading tools',
                'desc' => 'Free forex calculators & planners',
                'route' => 'trading.tools',
                'icon' => 'fa-solid fa-calculator',
            ],
        ];
    }
}
