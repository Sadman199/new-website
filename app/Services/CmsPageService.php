<?php

namespace App\Services;

use App\Models\CmsPage;
use App\Models\CmsPageSection;
use App\Support\CmsSectionRegistry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CmsPageService
{
    public function findPublishedBySlug(string $slug): ?CmsPage
    {
        return CmsPage::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->with('sections')
            ->first();
    }

    public function savePage(CmsPage $page, array $data, array $sections = []): CmsPage
    {
        return DB::transaction(function () use ($page, $data, $sections) {
            $page->fill([
                'title' => $data['title'],
                'slug' => $this->normalizeSlug($data['slug'] ?? $data['title']),
                'template' => $data['template'] ?? 'default',
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'status' => $data['status'] ?? 'draft',
            ])->save();

            $this->syncSections($page, $sections);

            return $page->fresh('sections');
        });
    }

    public function syncSections(CmsPage $page, array $sections): void
    {
        $page->sections()->delete();

        foreach (array_values($sections) as $index => $section) {
            $type = $section['section_type'] ?? $section['type'] ?? null;

            if (! $type || ! CmsSectionRegistry::isValidType($type)) {
                continue;
            }

            $data = $section['section_data'] ?? $section['data'] ?? [];
            $data = $this->normalizeSectionData($type, is_array($data) ? $data : []);

            CmsPageSection::create([
                'page_id' => $page->id,
                'section_type' => $type,
                'section_data' => $data,
                'sort_order' => $index,
            ]);
        }
    }

    public function normalizeSectionData(string $type, array $data): array
    {
        $defaults = CmsSectionRegistry::defaults($type);
        $merged = array_merge($defaults, $data);

        return match ($type) {
            'hero' => [
                'eyebrow' => (string) ($merged['eyebrow'] ?? ''),
                'headline' => (string) ($merged['headline'] ?? ''),
                'subheadline' => (string) ($merged['subheadline'] ?? ''),
                'background_style' => in_array($merged['background_style'] ?? '', ['dark', 'light'], true)
                    ? $merged['background_style']
                    : 'dark',
                'cta_label' => (string) ($merged['cta_label'] ?? ''),
                'cta_url' => (string) ($merged['cta_url'] ?? ''),
                'secondary_cta_label' => (string) ($merged['secondary_cta_label'] ?? ''),
                'secondary_cta_url' => (string) ($merged['secondary_cta_url'] ?? ''),
                'metrics' => $this->normalizeMetrics($merged['metrics'] ?? []),
            ],
            'text_content' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'body' => (string) ($merged['body'] ?? ''),
                'align' => in_array($merged['align'] ?? '', ['left', 'center'], true)
                    ? $merged['align']
                    : 'left',
            ],
            'image_text' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'body' => (string) ($merged['body'] ?? ''),
                'image' => (string) ($merged['image'] ?? ''),
                'image_alt' => (string) ($merged['image_alt'] ?? ''),
                'image_position' => ($merged['image_position'] ?? '') === 'left' ? 'left' : 'right',
            ],
            'cards' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'subheading' => (string) ($merged['subheading'] ?? ''),
                'columns' => max(2, min(4, (int) ($merged['columns'] ?? 3))),
                'items' => $this->normalizeItems($merged['items'] ?? [], ['title', 'text', 'icon', 'url']),
            ],
            'statistics' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'items' => $this->normalizeItems($merged['items'] ?? [], ['value', 'label', 'tone']),
            ],
            'faq' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'items' => $this->normalizeItems($merged['items'] ?? [], ['question', 'answer']),
            ],
            'timeline' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'items' => $this->normalizeItems($merged['items'] ?? [], ['year', 'title', 'text']),
            ],
            'team_members' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'subheading' => (string) ($merged['subheading'] ?? ''),
                'items' => $this->normalizeItems($merged['items'] ?? [], ['name', 'role', 'photo', 'bio']),
            ],
            'table' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'caption' => (string) ($merged['caption'] ?? ''),
                'headers' => array_values(array_filter(array_map('strval', Arr::wrap($merged['headers'] ?? [])))),
                'rows' => collect(Arr::wrap($merged['rows'] ?? []))
                    ->map(fn ($row) => array_values(array_map('strval', Arr::wrap($row))))
                    ->filter(fn ($row) => count(array_filter($row)) > 0)
                    ->values()
                    ->all(),
            ],
            'cta' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'text' => (string) ($merged['text'] ?? ''),
                'button_label' => (string) ($merged['button_label'] ?? ''),
                'button_url' => (string) ($merged['button_url'] ?? ''),
                'style' => in_array($merged['style'] ?? '', ['primary', 'dark'], true)
                    ? $merged['style']
                    : 'primary',
            ],
            'contact_form' => [
                'heading' => (string) ($merged['heading'] ?? 'Contact Us'),
                'subheading' => (string) ($merged['subheading'] ?? ''),
                'show_info_cards' => filter_var($merged['show_info_cards'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ],
            'glossary' => [
                'heading' => (string) ($merged['heading'] ?? ''),
                'intro' => (string) ($merged['intro'] ?? ''),
                'items' => $this->normalizeItems($merged['items'] ?? [], ['term', 'definition']),
            ],
            default => $merged,
        };
    }

    protected function normalizeMetrics(array $metrics): array
    {
        return collect($metrics)
            ->map(function ($item) {
                if (! is_array($item)) {
                    return null;
                }

                $label = trim((string) ($item['label'] ?? ''));
                $value = trim((string) ($item['value'] ?? ''));

                if ($label === '' && $value === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'value' => $value,
                    'tone' => in_array($item['tone'] ?? '', ['highlight', ''], true)
                        ? ($item['tone'] ?: null)
                        : null,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function normalizeItems(array $items, array $keys): array
    {
        return collect($items)
            ->map(function ($item) use ($keys) {
                if (! is_array($item)) {
                    return null;
                }

                $normalized = [];
                foreach ($keys as $key) {
                    $normalized[$key] = (string) ($item[$key] ?? '');
                }

                if (count(array_filter($normalized)) === 0) {
                    return null;
                }

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();
    }

    public function normalizeSlug(string $value): string
    {
        return Str::slug($value);
    }

    public function slugIsAvailable(string $slug, ?int $ignoreId = null): bool
    {
        $slug = $this->normalizeSlug($slug);

        if ($slug === '' || in_array($slug, CmsSectionRegistry::reservedSlugs(), true)) {
            return false;
        }

        $query = CmsPage::query()->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return ! $query->exists();
    }
}
