<?php

namespace App\Support;

class SiteJsonLd
{
    /** @return array<string, mixed> */
    public static function globalGraph(): array
    {
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $orgId = $siteUrl.'#organization';
        $websiteId = $siteUrl.'#website';

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                self::organization($orgId, $siteUrl),
                self::website($websiteId, $orgId, $siteUrl),
            ],
        ];
    }

    /** @return array<string, mixed> */
    public static function organization(string $orgId, string $siteUrl): array
    {
        return array_filter([
            '@type' => 'Organization',
            '@id' => $orgId,
            'name' => SiteTheme::siteName(),
            'url' => $siteUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => SiteTheme::logoUrl(),
            ],
            'description' => SiteTheme::siteTagline(),
            'contactPoint' => SiteTheme::contactPhone() !== ''
                ? [
                    '@type' => 'ContactPoint',
                    'telephone' => SiteTheme::contactPhone(),
                    'contactType' => 'customer support',
                    'availableLanguage' => ['English'],
                ]
                : null,
            'sameAs' => [
                'https://twitter.com/BrokersCourt',
            ],
        ]);
    }

    /** @return array<string, mixed> */
    public static function website(string $websiteId, string $orgId, string $siteUrl): array
    {
        $searchUrl = route('search', [], false);

        return [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $siteUrl,
            'name' => SiteTheme::siteName(),
            'description' => SiteTheme::siteTagline(),
            'publisher' => ['@id' => $orgId],
            'inLanguage' => 'en',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $siteUrl.$searchUrl.'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}
