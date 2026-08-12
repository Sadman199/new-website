<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ad;
use App\Services\Admin\PublicUploadService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminAdController extends AdminController
{
    public function __construct(protected PublicUploadService $uploads)
    {
    }

    public function index(Request $request)
    {
        $query = Ad::query()->orderByDesc('priority')->orderByDesc('id');

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($request->filled('active')) {
            $query->where('is_active', (bool) $request->get('active'));
        }

        $ads = $this->paginateWithSearch($query, $request, ['title', 'category'], 20);

        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        $ad = new Ad([
            'type' => 'popup',
            'position' => 'popup',
            'is_active' => true,
            'priority' => 0,
            'trigger_type' => 'scroll',
            'trigger_value' => 50,
            'repeatable' => false,
        ]);

        return view('admin.ads.form', compact('ad'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploads->storeFromRequest($request, 'image', 'uploads', 'ad_');
        $data['pages'] = $this->parsePages($request->input('pages'));

        Ad::create($data);

        return $this->flashSuccess('admin_ads_index', 'Ad created successfully.');
    }

    public function edit($id)
    {
        $ad = $this->findOrFail(Ad::class, $id);

        return view('admin.ads.form', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $ad = $this->findOrFail(Ad::class, $id);
        $data = $this->validated($request, $ad->id);
        $data['pages'] = $this->parsePages($request->input('pages'));
        $data['image'] = $this->uploads->replaceFromRequest(
            $request,
            'image',
            $ad->image,
            'uploads',
            'ad_'
        );

        $ad->update($data);

        return $this->flashSuccess('admin_ads_index', 'Ad updated successfully.');
    }

    public function destroy($id)
    {
        $ad = $this->findOrFail(Ad::class, $id);
        $this->uploads->delete($ad->image ? 'uploads/' . ltrim($ad->image, '/') : null);
        $ad->delete();

        return $this->flashSuccess('admin_ads_index', 'Ad deleted successfully.');
    }

    public function toggle($id)
    {
        $ad = $this->findOrFail(Ad::class, $id);
        $ad->is_active = ! $ad->is_active;
        $ad->save();

        return $this->flashBack($ad->is_active ? 'Ad activated.' : 'Ad deactivated.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(['banner', 'text', 'image', 'video', 'custom', 'popup'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'html_code' => 'nullable|string',
            'video_url' => 'nullable|url|max:255',
            'link' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'position' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:9999',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'trigger_type' => ['nullable', Rule::in(['time', 'scroll', 'stay'])],
            'trigger_value' => 'nullable|integer|min:0|max:10000',
            'repeatable' => 'nullable|boolean',
            'category' => 'nullable|string|max:100',
            'pages' => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['repeatable'] = $request->boolean('repeatable');
        $data['priority'] = (int) ($data['priority'] ?? 0);
        $data['position'] = $data['position'] ?: ($data['type'] === 'popup' ? 'popup' : 'sidebar');
        $data['trigger_type'] = $data['trigger_type'] ?? 'scroll';
        $data['trigger_value'] = isset($data['trigger_value']) ? (int) $data['trigger_value'] : 50;

        unset($data['image']);

        return $data;
    }

    private function parsePages(?string $raw): ?array
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $pages = collect(preg_split('/[\r\n,]+/', $raw))
            ->map(fn ($p) => trim($p))
            ->filter()
            ->values()
            ->all();

        return $pages ?: null;
    }
}
