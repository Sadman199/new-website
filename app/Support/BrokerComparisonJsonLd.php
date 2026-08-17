<?php

namespace App\Support;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Services\BrokerOgImageService;

class BrokerComparisonJsonLd
{
    /**
     * @param  array<string, mixed>  $comparison
     * @return array<string, mixed>
     */
    public static function graph(
        Broker $broker1,
        Broker $broker2,
        array $comparison,
        string $shareUrl,
        string $mode = 'compare'
    ): array {
        $isBattle = $mode === 'battle';
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $orgId = $siteUrl.'#organization';
        $websiteId = $siteUrl.'#website';
        $pageId = $shareUrl.'#webpage';
        $listId = $shareUrl.'#itemlist';
        $breadcrumbId = $shareUrl.'#breadcrumb';

        $left = $comparison['broker1'] ?? [];
        $right = $comparison['broker2'] ?? [];
        $leftName = $left['name'] ?? 'Broker';
        $rightName = $right['name'] ?? 'Broker';
        $pairLabel = $leftName.' vs '.$rightName;
        $year = date('Y');

        $pageName = $isBattle
            ? $pairLabel.' '.$year.' – Broker Battle | BrokersCourt'
            : $pairLabel.' comparison';

        $pageDescription = $isBattle
            ? 'Compare '.$leftName.' and '.$rightName.' across regulation, spreads, fees, platforms, trading conditions and more. See which broker wins the BrokersCourt Battle.'
            : 'Side-by-side comparison of fees, regulation, platforms, and safety scores.';

        $hubName = $isBattle ? 'Compare brokers' : 'Compare brokers';
        $hubUrl = route('broker.comparison');

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
                    'name' => $pageName,
                    'description' => $pageDescription,
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
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $hubName, 'item' => $hubUrl],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $isBattle ? $pairLabel.' battle' : $pairLabel],
                    ],
                ],
                [
                    '@type' => 'ItemList',
                    '@id' => $listId,
                    'name' => $isBattle ? $pairLabel.' Broker Battle' : $pairLabel,
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
