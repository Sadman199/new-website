<?php

namespace App\Support;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\Faq;
use App\Models\Review;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BrokerReviewJsonLd
{
    /**
     * @param  Collection<int, Review>|iterable<int, Review>  $approvedReviews
     * @param  Collection<int, Faq>|iterable<int, Faq>  $faqs
     * @param  array<string, mixed>  $reviewStats
     * @param  array<int, array<string, mixed>>  $editorialTeam
     * @param  array<string, mixed>  $snapshot
     * @return array<string, mixed>
     */
    public static function graph(
        Broker $broker,
        iterable $approvedReviews,
        iterable $faqs,
        array $reviewStats = [],
        array $editorialTeam = [],
        array $snapshot = [],
    ): array {
        $canonical = route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]);
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $orgId = $siteUrl.'#organization';
        $websiteId = $siteUrl.'#website';
        $pageId = $canonical.'#webpage';
        $brokerId = $canonical.'#broker';
        $breadcrumbId = $canonical.'#breadcrumb';
        $faqId = $canonical.'#faq';

        $reviews = Collection::make($approvedReviews)->values();
        $faqItems = Collection::make($faqs)->values();

        $graph = [
            self::organization($orgId, $siteUrl),
            self::website($websiteId, $orgId, $siteUrl),
            self::webPage($pageId, $canonical, $broker, $orgId, $brokerId, $breadcrumbId),
            self::breadcrumb($breadcrumbId, $canonical, $broker),
            self::financialService($brokerId, $canonical, $broker, $reviews, $reviewStats, $snapshot),
            self::editorialReview($canonical.'#editorial-review', $brokerId, $orgId, $broker, $editorialTeam, $snapshot),
            self::faqPage($faqId, $canonical, $faqItems),
        ];

        foreach ($reviews->take(5) as $index => $review) {
            $graph[] = self::userReview($canonical.'#user-review-'.($index + 1), $brokerId, $review);
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter($graph)),
        ];
    }

    /** @return array<string, mixed> */
    private static function organization(string $orgId, string $siteUrl): array
    {
        $logo = SiteTheme::logoUrl();

        return [
            '@type' => 'Organization',
            '@id' => $orgId,
            'name' => SiteTheme::siteName(),
            'url' => $siteUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo,
            ],
            'sameAs' => [
                'https://twitter.com/BrokersCourt',
            ],
        ];
    }

    /** @return array<string, mixed> */
    private static function website(string $websiteId, string $orgId, string $siteUrl): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $siteUrl,
            'name' => SiteTheme::siteName(),
            'publisher' => ['@id' => $orgId],
        ];
    }

    /** @return array<string, mixed> */
    private static function webPage(
        string $pageId,
        string $canonical,
        Broker $broker,
        string $orgId,
        string $brokerId,
        string $breadcrumbId,
    ): array {
        $description = self::plain($broker->meta_description ?: $broker->short_description ?: $broker->description, 300);

        return array_filter([
            '@type' => 'WebPage',
            '@id' => $pageId,
            'url' => $canonical,
            'name' => $broker->meta_title ?: $broker->title ?: ($broker->name.' review'),
            'description' => $description !== '' ? $description : null,
            'inLanguage' => 'en',
            'isPartOf' => ['@id' => rtrim((string) config('app.url'), '/').'#website'],
            'about' => ['@id' => $brokerId],
            'breadcrumb' => ['@id' => $breadcrumbId],
            'publisher' => ['@id' => $orgId],
            'dateModified' => optional($broker->updated_at)?->toAtomString(),
        ]);
    }

    /** @return array<string, mixed> */
    private static function breadcrumb(string $breadcrumbId, string $canonical, Broker $broker): array
    {
        return [
            '@type' => 'BreadcrumbList',
            '@id' => $breadcrumbId,
            'itemListElement' => [
                self::crumb(1, 'Home', route('home')),
                self::crumb(2, 'Broker reviews', route('broker.reviews.index')),
                self::crumb(3, $broker->name.' review', $canonical),
            ],
        ];
    }

    /** @return array<string, mixed> */
    private static function crumb(int $position, string $name, string $url): array
    {
        return [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $name,
            'item' => $url,
        ];
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  array<string, mixed>  $reviewStats
     * @param  array<string, mixed>  $snapshot
     * @return array<string, mixed>
     */
    private static function financialService(
        string $brokerId,
        string $canonical,
        Broker $broker,
        Collection $reviews,
        array $reviewStats,
        array $snapshot,
    ): array {
        $image = $broker->ogShareImageUrl();
        $website = self::absolute($broker->url ?: $broker->visit_site);
        $description = self::plain($broker->short_description ?: $broker->description, 300);

        $data = array_filter([
            '@type' => 'FinancialService',
            '@id' => $brokerId,
            'name' => $broker->name,
            'url' => $website ?: $canonical,
            'image' => $image,
            'description' => $description !== '' ? $description : null,
            'foundingDate' => $broker->year_founded ? (string) $broker->year_founded : null,
            'areaServed' => $broker->country ? strip_tags((string) $broker->country) : null,
        ]);

        if (! empty($snapshot['is_scam'])) {
            $data['disambiguatingDescription'] = self::plain($broker->scam_reason, 200)
                ?: 'Flagged as high-risk. Verify independently before depositing funds.';
        }

        $aggregate = self::aggregateRating($reviews, $reviewStats, (bool) ($snapshot['is_scam'] ?? false));
        if ($aggregate !== null) {
            $data['aggregateRating'] = $aggregate;
        }

        return $data;
    }

    /**
     * @param  array<int, array<string, mixed>>  $editorialTeam
     * @param  array<string, mixed>  $snapshot
     * @return array<string, mixed>|null
     */
    private static function editorialReview(
        string $reviewId,
        string $brokerId,
        string $orgId,
        Broker $broker,
        array $editorialTeam,
        array $snapshot,
    ): ?array {
        $rating = self::clampRating($broker->rating);
        $body = self::plain($broker->verdict ?: $broker->short_description ?: $broker->description, 400);

        if ($rating === null && $body === '') {
            return null;
        }

        $author = self::editorialAuthor($editorialTeam, $orgId);

        return array_filter([
            '@type' => 'Review',
            '@id' => $reviewId,
            'itemReviewed' => ['@id' => $brokerId],
            'author' => $author,
            'publisher' => ['@id' => $orgId],
            'dateModified' => optional($broker->updated_at)?->toAtomString(),
            'reviewBody' => $body !== '' ? $body : null,
            'reviewRating' => $rating !== null ? self::rating($rating) : null,
        ]);
    }

    /**
     * @param  Collection<int, Faq>  $faqs
     * @return array<string, mixed>|null
     */
    private static function faqPage(string $faqId, string $canonical, Collection $faqs): ?array
    {
        $questions = [];

        foreach ($faqs->take(15) as $faq) {
            $name = self::plain($faq->faq_title ?? '', 180);
            $answer = self::plain($faq->faq_detail ?? '', 600);

            if ($name === '' || $answer === '') {
                continue;
            }

            $questions[] = [
                '@type' => 'Question',
                'name' => $name,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        }

        if ($questions === []) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            '@id' => $faqId,
            'url' => $canonical,
            'mainEntity' => $questions,
        ];
    }

    /** @return array<string, mixed>|null */
    private static function userReview(string $reviewId, string $brokerId, Review $review): ?array
    {
        $rating = self::clampRating($review->rating);
        $body = self::plain($review->description, 400);
        $authorName = trim((string) ($review->name ?: 'Trader'));

        if ($rating === null && $body === '') {
            return null;
        }

        return array_filter([
            '@type' => 'Review',
            '@id' => $reviewId,
            'itemReviewed' => ['@id' => $brokerId],
            'author' => [
                '@type' => 'Person',
                'name' => $authorName !== '' ? $authorName : 'Trader',
            ],
            'datePublished' => optional($review->created_at)?->toAtomString(),
            'reviewBody' => $body !== '' ? $body : null,
            'reviewRating' => $rating !== null ? self::rating($rating) : null,
        ]);
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  array<string, mixed>  $reviewStats
     * @return array<string, mixed>|null
     */
    private static function aggregateRating(Collection $reviews, array $reviewStats, bool $isScam): ?array
    {
        if ($isScam) {
            return null;
        }

        $count = (int) ($reviewStats['count'] ?? $reviews->count());
        $average = (float) ($reviewStats['average'] ?? $reviews->avg('rating') ?? 0);
        $rating = self::clampRating($average);

        if ($count < 1 || $rating === null) {
            return null;
        }

        return [
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'bestRating' => 5,
            'worstRating' => 1,
            'ratingCount' => $count,
            'reviewCount' => $count,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $editorialTeam
     * @return array<string, mixed>
     */
    private static function editorialAuthor(array $editorialTeam, string $orgId): array
    {
        foreach ($editorialTeam as $member) {
            if (($member['role'] ?? '') !== 'written') {
                continue;
            }

            $name = trim((string) ($member['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            return array_filter([
                '@type' => 'Person',
                'name' => $name,
                'image' => self::imageUrl($member['photo'] ?? null),
            ]);
        }

        return ['@id' => $orgId];
    }

    /** @return array<string, mixed> */
    private static function rating(float $value): array
    {
        return [
            '@type' => 'Rating',
            'ratingValue' => $value,
            'bestRating' => 5,
            'worstRating' => 1,
        ];
    }

    private static function clampRating(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $rating = round((float) $value, 1);

        if ($rating < 1 || $rating > 5) {
            return null;
        }

        return $rating;
    }

    private static function imageUrl(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }

        return SiteTheme::ogImageUrl($path);
    }

    private static function absolute(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url($url);
    }

    private static function plain(?string $html, int $limit = 400): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');

        if ($text === '') {
            return '';
        }

        return Str::limit($text, $limit, '');
    }
}
