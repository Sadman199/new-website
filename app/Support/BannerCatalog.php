<?php

namespace App\Support;

use App\Models\CmsPage;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Schema;
use Throwable;

class BannerCatalog
{
    /** @return array<string, string> */
    public static function placements(?string $current = null): array
    {
        $flat = [];

        foreach (self::placementGroups($current) as $items) {
            foreach ($items as $key => $label) {
                $flat[$key] = $label;
            }
        }

        return $flat;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function placementGroups(?string $current = null): array
    {
        $groups = [
            'Homepage' => self::homepagePlacements(),
            'Site pages' => self::sitePagePlacements(),
            'CMS pages' => self::cmsPagePlacements(),
            'Blog sections' => self::blogSectionPlacements(),
            'Slots' => [
                'sidebar' => 'Sidebar (any page)',
                'other' => 'Other / unlisted',
            ],
        ];

        $groups = array_filter($groups, fn (array $items) => $items !== []);

        if ($current && $current !== '') {
            $known = [];
            foreach ($groups as $items) {
                $known += $items;
            }
            if (! array_key_exists($current, $known)) {
                $groups['Currently saved'] = [$current => $current];
            }
        }

        return $groups;
    }

    /** @return array<string, string> */
    public static function pagePaths(): array
    {
        $paths = [
            '/' => 'Homepage',
            '/broker-reviews' => 'Broker reviews',
            '/best-brokers' => 'Best brokers',
            '/top-brokers' => 'Top brokers',
            '/prop-firms' => 'Prop firms',
            '/blog' => 'Blog',
            '/broker-promos' => 'Promotions',
            '/find-my-broker' => 'Find my broker',
            '/compare' => 'Compare brokers',
            '/calculators' => 'Calculators',
            '/awards' => 'Awards',
            '/regulated-brokers' => 'Regulated brokers',
            '/scam-brokers' => 'Scam brokers',
            '/about' => 'About',
            '/our-team' => 'Our team',
            '/our-methodology' => 'Methodology',
            '/contact' => 'Contact',
        ];

        foreach (self::cmsPagePlacements() as $label) {
            if (preg_match('/\((\/[^)]+)\)$/', $label, $match)) {
                $paths[$match[1]] = preg_replace('/\s*\(\/[^)]+\)$/', '', $label) ?: $label;
            }
        }

        foreach (self::blogSectionPlacements() as $label) {
            if (preg_match('/\((\/[^)]+)\)$/', $label, $match)) {
                $paths[$match[1]] = preg_replace('/\s*\(\/[^)]+\)$/', '', $label) ?: $label;
            }
        }

        return $paths;
    }

    public static function isValidPlacement(?string $key, ?string $current = null): bool
    {
        if ($key === null || $key === '') {
            return false;
        }

        return array_key_exists($key, self::placements($current));
    }

    /** @return array<string, string> */
    public static function types(): array
    {
        return [
            'promotional' => 'Promotional',
            'announcement' => 'Announcement',
            'offer' => 'Offer',
            'custom' => 'Custom',
        ];
    }

    /** @return array<string, string> */
    public static function targeting(): array
    {
        return [
            'website_wide' => 'Website-wide',
            'specific_broker' => 'Specific Broker',
            'multiple_brokers' => 'Multiple Brokers',
            'specific_page' => 'Specific Page',
        ];
    }

    public static function placementLabel(?string $key): string
    {
        return self::placements($key)[$key] ?? (string) $key;
    }

    public static function typeLabel(?string $key): string
    {
        return self::types()[$key] ?? (string) $key;
    }

    public static function targetingLabel(?string $key): string
    {
        return self::targeting()[$key] ?? (string) $key;
    }

    public static function formats(): array
    {
        return [
            'image' => 'Image banner',
            'html' => 'HTML template',
        ];
    }

    public static function formatLabel(?string $key): string
    {
        return self::formats()[$key] ?? (string) $key;
    }

    public static function isHtml(?string $format): bool
    {
        return $format === 'html';
    }

    public static function requiresBrokers(?string $targeting): bool
    {
        return in_array($targeting, ['specific_broker', 'multiple_brokers'], true);
    }

    /** @return array<string, string> */
    private static function homepagePlacements(): array
    {
        return [
            'home' => 'Homepage',
            'homepage_hero' => 'Homepage — Hero',
            'homepage_section' => 'Homepage — In-page section',
        ];
    }

    /** @return array<string, string> */
    private static function sitePagePlacements(): array
    {
        return [
            'broker_listing' => 'Broker reviews',
            'broker_details' => 'Broker review pages',
            'brokers_best' => 'Best brokers',
            'brokers_top' => 'Top brokers',
            'prop_firms' => 'Prop firms',
            'blog' => 'Blog',
            'blog_details' => 'Blog article pages',
            'promotions' => 'Promotions',
            'find_my_broker' => 'Find my broker',
            'compare' => 'Compare brokers',
            'calculators' => 'Calculators',
            'awards' => 'Awards',
            'regulated_brokers' => 'Regulated brokers',
            'scam_brokers' => 'Scam brokers',
            'about' => 'About',
            'authors' => 'Our team',
            'methodology' => 'Methodology',
            'contact' => 'Contact',
        ];
    }

    /** @return array<string, string> */
    private static function cmsPagePlacements(): array
    {
        try {
            if (! Schema::hasTable('cms_pages')) {
                return [];
            }

            $reserved = CmsSectionRegistry::reservedSlugs();

            return CmsPage::query()
                ->where('status', 'published')
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderBy('title')
                ->get(['id', 'title', 'slug'])
                ->filter(fn (CmsPage $page) => ! in_array($page->slug, $reserved, true))
                ->mapWithKeys(function (CmsPage $page) {
                    $path = '/'.$page->slug;

                    return ['cms:'.$page->id => $page->title.' ('.$path.')'];
                })
                ->all();
        } catch (Throwable) {
            return [];
        }
    }

    /** @return array<string, string> */
    private static function blogSectionPlacements(): array
    {
        try {
            if (! Schema::hasTable('sub_categories')) {
                return [];
            }

            return SubCategory::query()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderBy('sub_category_name')
                ->get(['id', 'sub_category_name', 'slug'])
                ->mapWithKeys(function (SubCategory $sub) {
                    $path = '/insights/'.$sub->slug;

                    return ['blog:'.$sub->id => $sub->sub_category_name.' ('.$path.')'];
                })
                ->all();
        } catch (Throwable) {
            return [];
        }
    }
}
