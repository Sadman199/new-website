<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminAdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::query()->orderByDesc('priority')->orderByDesc('id');

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($request->filled('active')) {
            $query->where('is_active', (bool) $request->get('active'));
        }

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        $ads = $query->paginate(20)->withQueryString();

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
        $data['image'] = $this->storeImage($request);
        $data['pages'] = $this->parsePages($request->input('pages'));

        Ad::create($data);

        return redirect()->route('admin_ads_index')->with('success', 'Ad created successfully.');
    }

    public function edit($id)
    {
        $ad = Ad::findOrFail($id);

        return view('admin.ads.form', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);
        $data = $this->validated($request, $ad->id);
        $data['pages'] = $this->parsePages($request->input('pages'));

        if ($request->hasFile('image')) {
            $this->deleteImageFile($ad->image);
            $data['image'] = $this->storeImage($request);
        }

        $ad->update($data);

        return redirect()->route('admin_ads_index')->with('success', 'Ad updated successfully.');
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        $this->deleteImageFile($ad->image);
        $ad->delete();

        return redirect()->route('admin_ads_index')->with('success', 'Ad deleted successfully.');
    }

    public function toggle($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->is_active = ! $ad->is_active;
        $ad->save();

        return redirect()->back()->with('success', $ad->is_active ? 'Ad activated.' : 'Ad deactivated.');
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

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $name = 'ad_' . time() . '_' . Str::random(6) . '.' . $file->extension();
        $dest = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), '/') . '/uploads/';

        if (! is_dir($dest)) {
            @mkdir($dest, 0755, true);
        }

        $file->move($dest, $name);

        return $name;
    }

    private function deleteImageFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), '/') . '/uploads/' . ltrim($filename, '/');
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
