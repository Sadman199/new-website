<?php

namespace App\Support;

use App\Models\SocialItem;
use Throwable;

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
    public static function webPage(string $canonical, string $title, string $description): array
    {
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => 'en',
            'isPartOf' => ['@id' => $siteUrl.'#website'],
            'publisher' => ['@id' => $siteUrl.'#organization'],
        ];
    }

    /** @return array<string, mixed> */
    public static function organization(string $orgId, string $siteUrl): array
    {
        $email = SiteTheme::contactEmail();
        $phone = SiteTheme::contactPhone();

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
            'email' => $email !== '' ? $email : null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Al Nahda 2',
                'addressLocality' => 'Dubai',
                'addressCountry' => 'AE',
            ],
            'contactPoint' => $phone !== ''
                ? array_filter([
                    '@type' => 'ContactPoint',
                    'telephone' => $phone,
                    'email' => $email !== '' ? $email : null,
                    'contactType' => 'customer support',
                    'availableLanguage' => ['English'],
                    'areaServed' => 'Worldwide',
                ])
                : null,
            'sameAs' => self::sameAsUrls(),
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

    /** @return list<string>|null */
    private static function sameAsUrls(): ?array
    {
        $urls = [];

        try {
            $urls = SocialItem::query()
                ->whereNotNull('url')
                ->where('url', '!=', '')
                ->pluck('url')
                ->map(fn ($url) => trim((string) $url))
                ->filter(fn (string $url) => $url !== '' && filter_var($url, FILTER_VALIDATE_URL))
                ->values()
                ->all();
        } catch (Throwable) {
            $urls = [];
        }

        if ($urls === []) {
            $urls = ['https://twitter.com/BrokersCourt'];
        }

        return array_values(array_unique($urls));
    }
}
