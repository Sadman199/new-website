<?php

namespace App\Services;

use App\Models\PropFirm;
use App\Models\PropFirmAttribute;
use App\Models\PropFirmCategory;
use App\Models\PropFirmModuleSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PropFirmIndexService
{
    public const PER_PAGE = 12;

    /** @return array<string, mixed> */
    public function buildIndex(Request $request, ?string $categorySlug = null): array
    {
        $settings = PropFirmModuleSetting::instance();
        $defaultSort = (string) $settings->get('default_sort_order', 'trust_score');

        $categories = PropFirmCategory::query()
            ->where('is_active', true)
            ->withCount(['propFirms' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $attributes = PropFirmAttribute::query()
            ->where('is_active', true)
            ->orderBy('group')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $activeCategory = $categorySlug
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $query = PropFirm::query()
            ->with(['category', 'attributes'])
            ->where('is_active', true);

        if ($activeCategory) {
            $query->where('prop_firm_category_id', $activeCategory->id);
        }

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('headquarters', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('attribute')) {
            $attributeSlug = (string) $request->get('attribute');
            $query->whereHas('attributes', fn ($q) => $q->where('slug', $attributeSlug));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('verified')) {
            $query->where('is_verified', true);
        }

        if ($request->boolean('instant')) {
            $query->whereHas('attributes', fn ($q) => $q->where('slug', 'instant-funding'));
        }

        $sort = (string) $request->get('sort', $defaultSort);
        $direction = $request->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['name', 'trust_score', 'overall_rating', 'max_fee', 'created_at', 'sort_order'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = $defaultSort;
        }

        $firms = $query->orderBy($sort, $direction)->paginate(self::PER_PAGE)->withQueryString();

        $stats = [
            'total' => PropFirm::where('is_active', true)->count(),
            'featured' => PropFirm::where('is_active', true)->where('is_featured', true)->count(),
            'verified' => PropFirm::where('is_active', true)->where('is_verified', true)->count(),
            'categories' => $categories->count(),
        ];

        return [
            'firms' => $firms,
            'categories' => $categories,
            'attributes' => $attributes,
            'activeCategory' => $activeCategory,
            'stats' => $stats,
            'sort' => $sort,
            'direction' => $direction,
            'featuredFirms' => $this->featuredSidebar(),
        ];
    }

    /** @return Collection<int, PropFirm> */
    protected function featuredSidebar(): Collection
    {
        return PropFirm::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('trust_score')
            ->take(4)
            ->get(['id', 'name', 'slug', 'logo', 'trust_score', 'max_funding', 'profit_split']);
    }

    /** @return array<string, mixed> */
    public function buildDetail(string $slug): array
    {
        $firm = PropFirm::query()
            ->with([
                'category',
                'attributes' => fn ($q) => $q->orderBy('group')->orderBy('sort_order'),
                'programs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'reviews' => fn ($q) => $q->where('status', 'approved')->latest(),
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $settings = PropFirmModuleSetting::instance();

        $related = PropFirm::query()
            ->where('is_active', true)
            ->where('id', '!=', $firm->id)
            ->when($firm->prop_firm_category_id, fn ($q) => $q->where('prop_firm_category_id', $firm->prop_firm_category_id))
            ->orderByDesc('trust_score')
            ->take(4)
            ->get(['id', 'name', 'slug', 'logo', 'trust_score', 'max_funding']);

        return compact('firm', 'settings', 'related');
    }
}
