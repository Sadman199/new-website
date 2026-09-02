<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\FaqRequest;
use App\Models\Broker;
use App\Models\Faq;
use Illuminate\View\View;

class AdminFaqController extends AdminResourceController
{
    protected function modelClass(): string
    {
        return Faq::class;
    }

    protected function formRequestClass(): string
    {
        return FaqRequest::class;
    }

    protected function indexRoute(): string
    {
        return 'admin_faq_show';
    }

    protected function views(): array
    {
        return [
            'index' => 'admin.faqs.show',
            'create' => 'admin.faqs.create',
            'edit' => 'admin.faqs.edit',
        ];
    }

    protected function indexCollectionKey(): string
    {
        return 'faq_data';
    }

    protected function editModelKey(): string
    {
        return 'faq_data';
    }

    protected function indexRelations(): array
    {
        return ['rLanguage', 'broker'];
    }

    public function show(): View
    {
        $request = request();
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'broker_id' => (string) $request->get('broker_id', ''),
            'sort' => (string) $request->get('sort', 'newest'),
        ];

        $query = $this->indexQuery();

        if ($filters['broker_id'] !== '') {
            $query->where('broker_id', $filters['broker_id']);
        }

        match ($filters['sort']) {
            'title' => $query->orderBy('faq_title'),
            default => $query->latest('id'),
        };

        $faq_data = $this->paginateWithSearch($query, $request, ['faq_title'], 12);

        return view($this->views()['index'], [
            'faq_data' => $faq_data,
            'filters' => $filters,
            'brokers' => $this->brokers(),
            'stats' => [
                'total' => Faq::query()->count(),
                'brokers' => Faq::query()->whereNotNull('broker_id')->select('broker_id')->distinct()->count(),
            ],
        ]);
    }

    public function view($id): View
    {
        $faq = Faq::query()->with(['rLanguage', 'broker'])->findOrFail($id);

        return view('admin.faqs.view', [
            'faq' => $faq,
            'faq_data' => $faq,
        ]);
    }

    protected function createViewData(): array
    {
        return [
            'faq' => new Faq(),
            'brokers' => $this->brokers(),
        ];
    }

    protected function editViewData($model): array
    {
        return [
            'faq' => $model,
            'brokers' => $this->brokers(),
        ];
    }

    private function brokers()
    {
        return Broker::query()->orderBy('name')->get(['id', 'name']);
    }
}
