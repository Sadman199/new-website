<?php

namespace App\Services;

use App\Models\BrokerTaxonomyTerm;
use App\Support\BrokerTaxonomy;
use App\Support\RichText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrokerTaxonomyTermService
{
    /**
     * Persist listing descriptions submitted from the broker form.
     *
     * Only selected terms submit a textarea. Empty text removes the stored
     * description; omitted keys are left untouched so other brokers' listings
     * keep their copy.
     */
    public function syncFromRequest(Request $request): void
    {
        $groups = [
            BrokerTaxonomyTerm::TYPE_CATEGORY => ['category_descriptions', BrokerTaxonomy::categorySlugs()],
            BrokerTaxonomyTerm::TYPE_REGION => ['region_descriptions', BrokerTaxonomy::regionSlugs()],
            BrokerTaxonomyTerm::TYPE_COUNTRY => ['country_descriptions', array_keys(BrokerAdminService::countryListingOptions())],
        ];

        $changed = false;

        foreach ($groups as $type => [$input, $allowedSlugs]) {
            $allowed = array_fill_keys($allowedSlugs, true);

            foreach ((array) $request->input($input, []) as $slug => $description) {
                $slug = is_string($slug) ? $slug : (string) $slug;

                if ($slug === '' || ! isset($allowed[$slug])) {
                    continue;
                }

                $this->upsert($type, $slug, is_string($description) ? $description : '');
                $changed = true;
            }
        }

        if ($changed) {
            $this->flushCaches();
        }
    }

    public function upsert(string $type, string $slug, string $description): void
    {
        $description = trim($description);

        if (RichText::toPlainText($description) === null) {
            BrokerTaxonomyTerm::query()
                ->where('type', $type)
                ->where('slug', $slug)
                ->delete();

            return;
        }

        BrokerTaxonomyTerm::query()->updateOrCreate(
            ['type' => $type, 'slug' => $slug],
            ['description' => $description]
        );
    }

    public function flushCaches(): void
    {
        BrokerTaxonomyTerm::flushMemoryCache();
        Cache::forget('top_brokers_index_page_v5');
        Cache::forget('top_brokers_index_page_v6');
    }
}
