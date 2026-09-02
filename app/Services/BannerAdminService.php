<?php

namespace App\Services;

use App\Models\Banner;
use App\Services\Admin\PublicUploadService;
use App\Support\BannerCatalog;
use Illuminate\Http\Request;

class BannerAdminService
{
    public function __construct(protected PublicUploadService $uploads)
    {
    }

    public function save(Banner $banner, Request $request): Banner
    {
        $targeting = (string) $request->input('targeting');
        $brokerIds = BannerCatalog::requiresBrokers($targeting)
            ? array_values(array_unique(array_filter(array_map('intval', (array) $request->input('broker_ids', [])), fn (int $id) => $id > 0)))
            : [];
        $single = (int) $request->input('broker_id', 0);
        if ($targeting === 'specific_broker' && $single > 0) {
            $brokerIds = [$single];
        }

        $isHtml = BannerCatalog::isHtml((string) $request->input('creative_format'));

        $banner->fill([
            'title' => $request->input('title'),
            'creative_format' => $isHtml ? 'html' : 'image',
            'html_content' => $isHtml ? (string) $request->input('html_content', '') : null,
            'banner_type' => $request->input('banner_type'),
            'targeting' => $targeting,
            'placement' => $request->input('placement'),
            'page_path' => $targeting === 'specific_page' ? $this->normalizePath((string) $request->input('page_path', '')) : null,
            'button_text' => $request->input('button_text') ?: null,
            'button_url' => $request->input('button_url') ?: null,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'priority' => (int) $request->input('priority', 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($isHtml) {
            $banner->desktop_image = $banner->desktop_image ?: '';
        } else {
            $desktop = $this->uploads->replaceFromRequest(
                $request,
                'desktop_image',
                $banner->desktop_image,
                'uploads/banners',
                'banner_'
            );
            if ($desktop) {
                $banner->desktop_image = $desktop;
            }

            $banner->mobile_image = $this->uploads->replaceFromRequest(
                $request,
                'mobile_image',
                $banner->mobile_image,
                'uploads/banners',
                'banner_m_'
            );
        }

        $banner->save();
        $banner->brokers()->sync($brokerIds);

        return $banner;
    }

    public function delete(Banner $banner): void
    {
        $this->uploads->delete($banner->desktop_image);
        $this->uploads->delete($banner->mobile_image);
        $banner->brokers()->detach();
        $banner->delete();
    }

    private function normalizePath(string $path): ?string
    {
        $path = trim($path);
        if ($path === '') {
            return null;
        }

        return str_starts_with($path, '/') ? $path : '/'.$path;
    }
}
