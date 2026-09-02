<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CmsPageRequest;
use App\Models\CmsPage;
use App\Services\CmsPageService;
use App\Support\CmsSectionRegistry;
use Illuminate\Http\Request;
use Throwable;

class AdminCmsPageController extends AdminController
{
    public function __construct(protected CmsPageService $cmsPages)
    {
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'status' => (string) $request->get('status', ''),
            'template' => (string) $request->get('template', ''),
            'sort' => (string) $request->get('sort', 'updated'),
        ];

        $query = CmsPage::query()->withCount('sections');

        if (in_array($filters['status'], ['draft', 'published'], true)) {
            $query->where('status', $filters['status']);
        }

        if ($filters['template'] !== '' && CmsSectionRegistry::isValidTemplate($filters['template'])) {
            $query->where('template', $filters['template']);
        }

        match ($filters['sort']) {
            'newest' => $query->latest('id'),
            'title' => $query->orderBy('title'),
            'sections' => $query->orderByDesc('sections_count'),
            default => $query->latest('updated_at'),
        };

        $pages = $this->paginateWithSearch($query, $request, ['title', 'slug'], 15);

        return view('admin.cms_pages.index', [
            'pages' => $pages,
            'filters' => $filters,
            'templates' => CmsSectionRegistry::TEMPLATES,
            'stats' => [
                'total' => CmsPage::query()->count(),
                'published' => CmsPage::query()->where('status', 'published')->count(),
                'draft' => CmsPage::query()->where('status', 'draft')->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.cms_pages.create', $this->editorData());
    }

    public function store(CmsPageRequest $request)
    {
        try {
            $page = $this->cmsPages->savePage(
                new CmsPage(),
                $request->validated(),
                $this->sectionsFromRequest($request)
            );
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create the page: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_cms_pages_edit', $page->id)
            ->with('success', 'Page created. You can keep editing it, or publish it when it is ready.');
    }

    public function view($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id, ['sections']);

        return view('admin.cms_pages.view', [
            'page' => $page,
        ]);
    }

    public function edit($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id, ['sections']);

        return view('admin.cms_pages.edit', $this->editorData($page));
    }

    public function update(CmsPageRequest $request, $id)
    {
        $page = $this->findOrFail(CmsPage::class, $id);

        try {
            $this->cmsPages->savePage(
                $page,
                $request->validated(),
                $this->sectionsFromRequest($request)
            );
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not save the page: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_cms_pages_edit', $page->id)
            ->with('success', 'Page saved.');
    }

    public function destroy($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id);
        $title = $page->title;
        $page->delete();

        return $this->flashSuccess('admin_cms_pages_index', '"'.$title.'" was deleted.');
    }

    public function toggleStatus($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id);
        $page->status = $page->status === 'published' ? 'draft' : 'published';
        $page->save();

        $message = $page->isPublished()
            ? '"'.$page->title.'" is now live on the site.'
            : '"'.$page->title.'" is now a draft and hidden from visitors.';

        return $this->flashBack($message);
    }

    /** @return array<string, mixed> */
    protected function editorData(?CmsPage $page = null): array
    {
        $page ??= new CmsPage(['status' => 'draft', 'template' => 'default']);

        $sections = [];
        if ($page->exists && $page->relationLoaded('sections')) {
            $sections = $page->sections->map(fn ($section) => [
                'section_type' => $section->section_type,
                'section_data' => $section->section_data ?? CmsSectionRegistry::defaults($section->section_type),
            ])->values()->all();
        }

        $oldPayload = old('sections_payload');
        if (is_string($oldPayload) && $oldPayload !== '') {
            $decoded = json_decode($oldPayload, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $sections = $decoded;
            }
        }

        $sectionTypes = CmsSectionRegistry::labels();

        return [
            'page' => $page,
            'sections' => $sections,
            'sectionTypes' => $sectionTypes,
            'sectionCatalog' => CmsSectionRegistry::adminCatalog(),
            'templates' => CmsSectionRegistry::TEMPLATES,
            'builderConfig' => [
                'types' => $sectionTypes,
                'catalog' => CmsSectionRegistry::adminCatalogFlat(),
                'defaults' => collect(array_keys($sectionTypes))
                    ->mapWithKeys(fn ($type) => [$type => CmsSectionRegistry::defaults($type)])
                    ->all(),
                'initial' => $sections,
            ],
        ];
    }

    protected function sectionsFromRequest(Request $request): array
    {
        $raw = $request->input('sections_payload');

        if ($raw === null || $raw === '') {
            return [];
        }

        if (is_array($raw)) {
            return $raw;
        }

        $sections = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($sections)) {
            return [];
        }

        return $sections;
    }
}
