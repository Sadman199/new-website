<?php

namespace App\Support;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Services\BrokerComparisonService;
use App\Services\BrokerOgImageService;

class BrokerComparisonJsonLd
{
    /**
     * @param  array<string, mixed>  $comparison
     * @return array<string, mixed>
     */
    public static function graph(Broker $broker1, Broker $broker2, array $comparison, string $shareUrl): array
    {
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $orgId = $siteUrl.'#organization';
        $websiteId = $siteUrl.'#website';
        $pageId = $shareUrl.'#webpage';
        $listId = $shareUrl.'#itemlist';
        $breadcrumbId = $shareUrl.'#breadcrumb';

        $left = $comparison['broker1'] ?? [];
        $right = $comparison['broker2'] ?? [];

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                [
                    '@type' => 'Organization',
                    '@id' => $orgId,
                    'name' => SiteTheme::siteName(),
                    'url' => $siteUrl,
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $websiteId,
                    'url' => $siteUrl,
                    'name' => SiteTheme::siteName(),
                    'publisher' => ['@id' => $orgId],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $pageId,
                    'url' => $shareUrl,
                    'name' => ($left['name'] ?? 'Broker').' vs '.($right['name'] ?? 'Broker').' comparison',
                    'description' => 'Side-by-side comparison of fees, regulation, platforms, and safety scores.',
                    'isPartOf' => ['@id' => $websiteId],
                    'breadcrumb' => ['@id' => $breadcrumbId],
                    'primaryImageOfPage' => [
                        '@type' => 'ImageObject',
                        'url' => $left['og_image'] ?? SiteTheme::logoUrl(),
                        'width' => BrokerOgImageService::WIDTH,
                        'height' => BrokerOgImageService::HEIGHT,
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => $breadcrumbId,
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $siteUrl],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Compare brokers', 'item' => route('broker.comparison')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => ($left['name'] ?? 'Broker').' vs '.($right['name'] ?? 'Broker')],
                    ],
                ],
                [
                    '@type' => 'ItemList',
                    '@id' => $listId,
                    'name' => ($left['name'] ?? 'Broker').' vs '.($right['name'] ?? 'Broker'),
                    'numberOfItems' => 2,
                    'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
                    'itemListElement' => [
                        self::listItem(1, $broker1, $left),
                        self::listItem(2, $broker2, $right),
                    ],
                ],
            ])),
        ];
    }

    /** @param array<string, mixed> $serialized */
    private static function listItem(int $position, Broker $broker, array $serialized): array
    {
        $url = route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]);

        return [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => $url,
            'name' => $broker->name,
            'item' => array_filter([
                '@type' => 'FinancialService',
                'name' => $broker->name,
                'url' => $url,
                'image' => $serialized['og_image'] ?? SiteTheme::ogImageUrl($broker->logo),
                'aggregateRating' => isset($serialized['rating']) && $serialized['rating'] !== null
                    ? [
                        '@type' => 'AggregateRating',
                        'ratingValue' => $serialized['rating'],
                        'bestRating' => 5,
                        'worstRating' => 1,
                        'ratingCount' => max(1, (int) ($serialized['review_count'] ?? 1)),
                    ]
                    : null,
            ]),
        ];
    }
}
