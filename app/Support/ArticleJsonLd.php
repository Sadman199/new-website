<?php

namespace App\Support;

use App\Models\Post;
use App\Services\EditorialAssignmentService;
use Illuminate\Support\Str;

class ArticleJsonLd
{
    /** @return array<string, mixed> */
    public static function graph(Post $post, string $canonical): array
    {
        $siteUrl = rtrim((string) config('app.url'), '/') ?: url('/');
        $pageId = $canonical.'#article';
        $image = $post->socialSharePath()
            ? SiteTheme::ogImageUrl($post->socialSharePath())
            : SiteTheme::logoUrl();

        $authorName = $post->author_name
            ?? EditorialAssignmentService::primaryWriterName($post)
            ?? SiteTheme::siteName();

        $published = ($post->publish_at ?? $post->created_at)?->toAtomString();
        $modified = $post->updated_at?->toAtomString() ?? $published;
        $schemaType = $post->schema_type ?: 'BlogPosting';
        if (! in_array($schemaType, ['Article', 'NewsArticle', 'BlogPosting'], true)) {
            $schemaType = 'BlogPosting';
        }

        $description = $post->excerpt ?: ($post->meta_description ?: $post->post_detail);

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => $schemaType,
                    '@id' => $pageId,
                    'headline' => $post->post_title,
                    'description' => Str::limit(strip_tags((string) $description), 300),
                    'url' => $canonical,
                    'mainEntityOfPage' => ['@id' => $canonical.'#webpage'],
                    'image' => [$image],
                    'datePublished' => $published,
                    'dateModified' => $modified,
                    'author' => [
                        '@type' => 'Person',
                        'name' => $authorName,
                    ],
                    'publisher' => ['@id' => $siteUrl.'#organization'],
                    'inLanguage' => 'en',
                    'articleSection' => $post->rSubCategory->sub_category_name ?? 'Insights',
                    'keywords' => $post->meta_keywords ?: null,
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $canonical.'#webpage',
                    'url' => $canonical,
                    'name' => $post->meta_title ?: $post->post_title,
                    'isPartOf' => ['@id' => $siteUrl.'#website'],
                    'breadcrumb' => [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => self::breadcrumbItems($post, $canonical, $siteUrl),
                    ],
                ],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private static function breadcrumbItems(Post $post, string $canonical, string $siteUrl): array
    {
        $items = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $siteUrl],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog')],
        ];

        if ($post->rSubCategory?->slug) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $post->rSubCategory->sub_category_name,
                'item' => route('category', $post->rSubCategory->slug),
            ];
        }

        $items[] = [
            '@type' => 'ListItem',
            'position' => count($items) + 1,
            'name' => Str::limit($post->post_title, 80),
            'item' => $canonical,
        ];

        return $items;
    }
}
