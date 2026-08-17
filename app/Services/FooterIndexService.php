<?php



namespace App\Services;



use App\Models\Broker;

use App\Models\Language;

use App\Models\Page;
use App\Support\SiteTheme;



class FooterIndexService

{

    /** @return array<string, mixed> */

    public function build(): array

    {

        return \Illuminate\Support\Facades\Cache::remember('footer_index_v6', 3600, function () {

            $page = $this->resolvePage();



            return [

                'brand' => $this->brand(),

                'cta' => $this->ctaBand(),

                'top_brokers' => $this->topBrokers(),

                'comparisons' => $this->comparisons(),

                'regions' => $this->regions(),

                'for_users' => $this->forUsers(),

                'contact' => $this->contact(),

                'social' => $this->socialLinks(),

                'legal' => $this->legalLinks($page),

                'disclaimer' => 'Trading forex, CFDs, and leveraged products carries significant risk and may not suit all investors. You may lose more than your deposit. Past performance is not indicative of future results. Content is for educational purposes only — not financial advice.',

                'affiliate' => 'BrokersCourt is an independent comparison site. We may receive compensation from featured brokers. Reviews remain editorially independent.',

            ];

        });

    }



    /** @return array<int, array<string, string>> */

    public function popularComparisons(): array

    {

        $brokers = Broker::query()

            ->where('is_scam', false)

            ->whereNotNull('slug')

            ->where('slug', '!=', '')

            ->orderByDesc('rating')

            ->limit(5)

            ->get(['name', 'slug']);



        if ($brokers->count() < 2) {

            return [];

        }



        $pairs = [];



        for ($i = 0; $i < $brokers->count() && count($pairs) < 8; $i++) {

            for ($j = $i + 1; $j < $brokers->count() && count($pairs) < 8; $j++) {

                $first = $brokers[$i];

                $second = $brokers[$j];



                $pairs[] = [

                    'label' => $first->name . ' vs ' . $second->name,

                    'url' => BrokerComparisonService::canonicalPairUrl($first->slug, $second->slug),

                ];

            }

        }



        return $pairs;

    }



    /** @return array<int, array<string, mixed>> */

    private function ctaBand(): array

    {

        return [

            [

                'label' => 'Find my broker',

                'description' => 'Get matched with regulated brokers',

                'url' => route('find_my_broker'),

                'icon' => 'fas fa-search',

                'primary' => true,

            ],

            [

                'label' => 'Compare brokers',

                'description' => 'Side-by-side fees & platforms',

                'url' => route('broker.comparison'),

                'icon' => 'fas fa-columns',

                'primary' => false,

            ],

            [

                'label' => 'Broker awards',

                'description' => 'Top-rated by category',

                'url' => route('awards.index'),

                'icon' => 'fas fa-trophy',

                'primary' => false,

            ],

        ];

    }



    /** @return array<string, string> */

    private function brand(): array
    {
        return [
            'name' => SiteTheme::siteName(),
            'tagline' => SiteTheme::siteTagline(),
            'logo' => SiteTheme::logoUrl(),
        ];
    }



    /** @return array{links: array<int, array<string, string|null>>, view_all: array<string, string>|null} */

    private function topBrokers(): array

    {

        $links = Broker::query()

            ->where('is_scam', false)

            ->whereNotNull('slug')

            ->where('slug', '!=', '')

            ->orderByDesc('rating')

            ->limit(8)

            ->get(['name', 'slug', 'rating'])

            ->map(fn (Broker $broker) => [

                'label' => $broker->name,

                'url' => route('broker_detail', ['slug' => $broker->slug]),

                'meta' => $broker->rating ? number_format((float) $broker->rating, 1) : null,

            ])

            ->values()

            ->all();



        return [

            'links' => $links,

            'view_all' => $links !== [] ? [

                'label' => 'All reviews',

                'url' => route('broker.reviews.index'),

            ] : null,

        ];

    }



    /** @return array<int, array<string, string>> */

    private function comparisons(): array

    {

        return array_values(array_filter([

            ['label' => 'Compare brokers', 'url' => route('broker.comparison')],

            ['label' => 'Find my broker', 'url' => route('find_my_broker')],

            ['label' => 'Best broker guides', 'url' => route('brokers.best.index')],

            ['label' => 'Broker awards', 'url' => route('awards.index')],

            ['label' => 'Regulated brokers', 'url' => route('regulated_brokers')],

        ]));

    }



    /**
     * Mirrors the region list used by the navbar mega menu.
     *
     * @return array<int, array<string, string|null>>
     */

    private function regions(): array

    {

        return collect(BrokerAdminService::listedRegionsWithFlags())

            ->map(fn (array $region, string $slug) => [

                'label' => $region['name'],

                'code' => $region['code'] ?? null,

                'url' => route('brokers.best', ['slug' => $slug]),

            ])

            ->values()

            ->all();

    }



    /** @return array<int, array<string, string>> */

    private function forUsers(): array

    {

        return [

            ['label' => 'Blog & news', 'url' => route('blog')],

            ['label' => 'Trading tools', 'url' => route('trading.tools')],

            ['label' => 'Prop firms', 'url' => route('prop_firms.index')],

            ['label' => 'Our methodology', 'url' => route('methodology')],

            ['label' => 'Our team', 'url' => route('authors')],

            ['label' => 'Contact', 'url' => route('contact')],

        ];

    }



    /** @return array<string, string> */

    private function contact(): array

    {

        return [
            'address' => 'Al Nahda 2, Dubai',
            'email' => 'info@brokerscourt.com',
            'phone' => SiteTheme::contactPhone(),
        ];

    }



    /** @return array<int, array<string, string>> */

    private function legalLinks(?Page $page): array

    {

        return [

            [

                'label' => ($page && $page->terms_status === 'Show') ? ($page->terms_title ?: 'Terms & Conditions') : 'Terms & Conditions',

                'url' => route('terms'),

            ],

            [

                'label' => ($page && $page->privacy_status === 'Show') ? ($page->privacy_title ?: 'Privacy Policy') : 'Privacy Policy',

                'url' => route('privacy'),

            ],

            [

                'label' => ($page && $page->disclaimer_status === 'Show') ? ($page->disclaimer_title ?: 'Disclaimer') : 'Disclaimer',

                'url' => route('disclaimer'),

            ],

        ];

    }



    /** @return array<int, array<string, string>> */

    private function socialLinks(): array

    {

        try {

            return \App\Models\SocialItem::query()

                ->whereNotNull('url')

                ->where('url', '!=', '')

                ->get(['name', 'url', 'icon'])

                ->map(fn ($item) => [

                    'name' => $item->name,

                    'url' => $item->url,

                    'icon' => $item->icon,

                ])

                ->values()

                ->all();

        } catch (\Throwable $e) {

            return [];

        }

    }



    private function resolvePage(): ?Page

    {

        try {

            return Page::where('language_id', $this->resolveLanguageId())->first();

        } catch (\Throwable $e) {

            return null;

        }

    }



    private function resolveLanguageId(): int

    {

        $shortName = session()->get('session_short_name')

            ?? optional(Language::where('is_default', 'Yes')->first())->short_name

            ?? 'en';



        return (int) (optional(Language::where('short_name', $shortName)->first())->id ?? 1);

    }

}

