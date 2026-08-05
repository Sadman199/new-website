<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Page;

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
            'topics' => $this->helpTopics(),
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

    /** @return array<int, array<string, string>> */
    private function channels(): array
    {
        return [
            [
                'label' => 'Email',
                'value' => 'info@brokerscourt.com',
                'href' => 'mailto:info@brokerscourt.com',
                'hint' => 'Best for detailed questions and document attachments.',
            ],
            [
                'label' => 'Phone',
                'value' => '+44 7577 309951',
                'href' => 'tel:+447577309951',
                'hint' => 'Speak with our team during office hours.',
            ],
            [
                'label' => 'Office',
                'value' => 'Al Nahda 2, Dubai',
                'href' => null,
                'hint' => 'Editorial and business correspondence.',
            ],
            [
                'label' => 'Hours',
                'value' => 'Mon–Fri, 9 AM – 5 PM EST',
                'href' => null,
                'hint' => 'We aim to reply within one business day.',
            ],
        ];
    }

    /** @return array<int, array<string, string>> */
    private function helpTopics(): array
    {
        return [
            [
                'title' => 'Broker reviews',
                'description' => 'Ask about our methodology, rating criteria, or suggest a broker for review.',
            ],
            [
                'title' => 'Partnerships & media',
                'description' => 'Reach out for business inquiries, press requests, or affiliate questions.',
            ],
            [
                'title' => 'Report a scam broker',
                'description' => 'Share evidence about suspicious brokers so we can investigate and warn traders.',
            ],
            [
                'title' => 'Site feedback',
                'description' => 'Tell us about bugs, missing data, or features you would like to see on BrokersCourt.',
            ],
        ];
    }
}
