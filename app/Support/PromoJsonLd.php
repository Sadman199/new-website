<?php

namespace App\Support;

use App\Http\Controllers\Front\BrokerController;
use App\Models\ForexBonus;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PromoJsonLd
{
    /**
     * @param  Collection<int, array<string, mixed>>|iterable<int, array<string, mixed>>  $cards
     * @return array<string, mixed>
     */
    public static function indexGraph(string $canonical, string $title, iterable $cards): array
    {
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $items = Collection::make($cards)->take(20)->values();

        $listElements = $items->map(function (array $card, int $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => $card['url'] ?? null,
                'name' => $card['title'] ?? 'Broker promo',
            ];
        })->all();

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'CollectionPage',
                    '@id' => $canonical.'#webpage',
                    'url' => $canonical,
                    'name' => $title,
                    'isPartOf' => ['@id' => $siteUrl.'#website'],
                    'breadcrumb' => [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $siteUrl],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Broker promos', 'item' => route('promotions.index')],
                        ],
                    ],
                ],
                [
                    '@type' => 'ItemList',
                    '@id' => $canonical.'#itemlist',
                    'name' => $title,
                    'numberOfItems' => $items->count(),
                    'itemListElement' => $listElements,
                ],
            ],
        ];
    }

    /** @return array<string, mixed> */
    public static function detailGraph(ForexBonus $bonus): array
    {
        $canonical = $bonus->detailUrl() ?: url()->current();
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $broker = $bonus->broker;
        $image = $bonus->feature_image
            ? SiteTheme::ogImageUrl('uploads/'.$bonus->feature_image)
            : ($broker?->ogShareImageUrl() ?: SiteTheme::logoUrl());

        $offer = array_filter([
            '@type' => 'Offer',
            'name' => $bonus->title,
            'description' => Str::limit(strip_tags((string) ($bonus->description ?: $bonus->headlineOffer())), 280),
            'url' => $canonical,
            'category' => $bonus->promo_type ?: 'Broker promotion',
            'availability' => $bonus->isActivePromotion()
                ? 'https://schema.org/InStock'
                : 'https://schema.org/SoldOut',
            'validThrough' => $bonus->expiry_date?->toAtomString(),
            'seller' => $broker ? [
                '@type' => 'Organization',
                'name' => $broker->name,
                'url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            ] : null,
        ]);

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebPage',
                    '@id' => $canonical.'#webpage',
                    'url' => $canonical,
                    'name' => $bonus->meta_title ?: $bonus->title,
                    'description' => Str::limit(strip_tags((string) ($bonus->meta_description ?: $bonus->description)), 160),
                    'image' => $image,
                    'breadcrumb' => [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $siteUrl],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Broker promos', 'item' => route('promotions.index')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $bonus->title],
                        ],
                    ],
                ],
                $offer,
            ],
        ];
    }
}
