<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Models\Broker;
use App\Services\BannerAdminService;
use App\Support\BannerCatalog;
use Illuminate\Http\Request;
use Throwable;

class AdminBannerController extends AdminController
{
    public function __construct(protected BannerAdminService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'placement' => (string) $request->get('placement', ''),
            'targeting' => (string) $request->get('targeting', ''),
            'banner_type' => (string) $request->get('banner_type', ''),
            'status' => (string) $request->get('status', ''),
            'broker_id' => (string) $request->get('broker_id', ''),
        ];

        $query = Banner::query()->with('brokers:id,name');

        if ($filters['placement'] !== '' && BannerCatalog::isValidPlacement($filters['placement'])) {
            $query->where('placement', $filters['placement']);
        }

        if ($filters['targeting'] !== '' && array_key_exists($filters['targeting'], BannerCatalog::targeting())) {
            $query->where('targeting', $filters['targeting']);
        }

        if ($filters['banner_type'] !== '' && array_key_exists($filters['banner_type'], BannerCatalog::types())) {
            $query->where('banner_type', $filters['banner_type']);
        }

        if ($filters['broker_id'] !== '') {
            $query->whereHas('brokers', fn ($q) => $q->where('brokers.id', $filters['broker_id']));
        }

        $today = now()->toDateString();
        match ($filters['status']) {
            'active' => $query->currentlyDisplayable(),
            'inactive' => $query->where('is_active', false),
            'scheduled' => $query->where('is_active', true)->whereDate('start_date', '>', $today),
            'expired' => $query->where('is_active', true)->whereDate('end_date', '<', $today),
            default => $query,
        };

        $query->ranked();

        $banners = $this->paginateWithSearch($query, $request, ['title', 'button_url', 'page_path'], 12);

        return view('admin.banners.index', [
            'banners' => $banners,
            'filters' => $filters,
            'placements' => BannerCatalog::placements(),
            'placementGroups' => BannerCatalog::placementGroups(),
            'types' => BannerCatalog::types(),
            'targetingOptions' => BannerCatalog::targeting(),
            'brokers' => Broker::query()->orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => Banner::query()->count(),
                'live' => Banner::query()->currentlyDisplayable()->count(),
                'scheduled' => Banner::query()->where('is_active', true)->whereDate('start_date', '>', $today)->count(),
                'expired' => Banner::query()->where('is_active', true)->whereDate('end_date', '<', $today)->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.banners.create', $this->formData(new Banner([
            'banner_type' => 'promotional',
            'creative_format' => 'image',
            'targeting' => 'website_wide',
            'placement' => 'home',
            'priority' => 0,
            'is_active' => true,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ])));
    }

    public function store(BannerRequest $request)
    {
        try {
            $this->service->save(new Banner(), $request);
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'Could not create the banner: '.$e->getMessage());
        }

        return $this->flashSuccess('admin_banners_index', 'Banner created.');
    }

    public function edit($id)
    {
        $banner = Banner::query()->with('brokers')->findOrFail($id);

        return view('admin.banners.edit', $this->formData($banner));
    }

    public function update(BannerRequest $request, $id)
    {
        $banner = Banner::query()->findOrFail($id);

        try {
            $this->service->save($banner, $request);
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'Could not save the banner: '.$e->getMessage());
        }

        return $this->flashSuccess('admin_banners_index', 'Banner saved.');
    }

    public function destroy($id)
    {
        $banner = Banner::query()->findOrFail($id);
        $title = $banner->title;
        $this->service->delete($banner);

        return $this->flashSuccess('admin_banners_index', '"'.$title.'" was deleted.');
    }

    public function toggle($id)
    {
        $banner = Banner::query()->findOrFail($id);
        $banner->is_active = ! $banner->is_active;
        $banner->save();

        return $this->flashBack($banner->is_active ? 'Banner activated.' : 'Banner deactivated.');
    }

    /** @return array<string, mixed> */
    protected function formData(Banner $banner): array
    {
        return [
            'banner' => $banner,
            'brokers' => Broker::query()->orderBy('name')->get(['id', 'name']),
            'placements' => BannerCatalog::placements($banner->placement),
            'placementGroups' => BannerCatalog::placementGroups($banner->placement),
            'pagePaths' => BannerCatalog::pagePaths(),
            'types' => BannerCatalog::types(),
            'formats' => BannerCatalog::formats(),
            'targetingOptions' => BannerCatalog::targeting(),
        ];
    }
}
