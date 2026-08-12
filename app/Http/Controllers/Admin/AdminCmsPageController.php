<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CmsPageRequest;
use App\Models\CmsPage;
use App\Services\CmsPageService;
use App\Support\CmsSectionRegistry;
use Illuminate\Http\Request;

class AdminCmsPageController extends AdminController
{
    public function __construct(protected CmsPageService $cmsPages)
    {
    }

    public function index(Request $request)
    {
        $query = CmsPage::query()->withCount('sections');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $pages = $this->paginateWithSearch($query, $request, ['title', 'slug'], 15);

        return view('admin.cms_pages.index', [
            'pages' => $pages,
            'stats' => [
                'total' => CmsPage::count(),
                'published' => CmsPage::where('status', 'published')->count(),
                'draft' => CmsPage::where('status', 'draft')->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.cms_pages.create', [
            'page' => new CmsPage(['status' => 'draft', 'template' => 'default']),
            'sections' => [],
            'sectionTypes' => CmsSectionRegistry::labels(),
            'sectionCatalog' => CmsSectionRegistry::adminCatalog(),
            'templates' => CmsSectionRegistry::TEMPLATES,
        ]);
    }

    public function store(CmsPageRequest $request)
    {
        $page = $this->cmsPages->savePage(
            new CmsPage(),
            $request->validated(),
            $this->sectionsFromRequest($request)
        );

        return redirect()
            ->route('admin_cms_pages_edit', $page->id)
            ->with('success', 'CMS page created successfully.');
    }

    public function edit($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id, ['sections']);

        return view('admin.cms_pages.edit', [
            'page' => $page,
            'sections' => $page->sections->map(fn ($section) => [
                'section_type' => $section->section_type,
                'section_data' => $section->section_data ?? CmsSectionRegistry::defaults($section->section_type),
            ])->values()->all(),
            'sectionTypes' => CmsSectionRegistry::labels(),
            'sectionCatalog' => CmsSectionRegistry::adminCatalog(),
            'templates' => CmsSectionRegistry::TEMPLATES,
        ]);
    }

    public function update(CmsPageRequest $request, $id)
    {
        $page = $this->findOrFail(CmsPage::class, $id);

        $this->cmsPages->savePage(
            $page,
            $request->validated(),
            $this->sectionsFromRequest($request)
        );

        return redirect()
            ->route('admin_cms_pages_edit', $page->id)
            ->with('success', 'CMS page updated successfully.');
    }

    public function destroy($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id);
        $page->delete();

        return $this->flashSuccess('admin_cms_pages_index', 'CMS page deleted.');
    }

    public function toggleStatus($id)
    {
        $page = $this->findOrFail(CmsPage::class, $id);
        $page->status = $page->status === 'published' ? 'draft' : 'published';
        $page->save();

        return $this->flashBack('Page status updated.');
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
