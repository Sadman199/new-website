<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AdminCrudService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class AdminResourceController extends AdminController
{
    public function __construct(protected AdminCrudService $crud)
    {
    }

    abstract protected function modelClass(): string;

    /** @return class-string<FormRequest> */
    abstract protected function formRequestClass(): string;

    abstract protected function indexRoute(): string;

    abstract protected function views(): array;

    abstract protected function indexCollectionKey(): string;

    abstract protected function editModelKey(): string;

    protected function indexRelations(): array
    {
        return [];
    }

    protected function indexOrder(): array
    {
        return ['id', 'desc'];
    }

    protected function createViewData(): array
    {
        return [];
    }

    protected function editViewData(Model $model): array
    {
        return [];
    }

    protected function attributesFromValidated(array $validated): array
    {
        return $validated;
    }

    protected function beforeCreate(Model $model, array $validated): void
    {
    }

    protected function beforeUpdate(Model $model, array $validated): void
    {
    }

    protected function beforeDelete(Model $model): void
    {
    }

    public function show(): View
    {
        $query = $this->indexQuery();

        [$column, $direction] = $this->indexOrder();
        $query->orderBy($column, $direction);

        $items = $query->get();

        return view($this->views()['index'], [
            $this->indexCollectionKey() => $items,
        ]);
    }

    public function create(): View
    {
        return view($this->views()['create'], $this->createViewData());
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $modelClass = $this->modelClass();

        /** @var Model $model */
        $model = new $modelClass();
        $model->fill($this->attributesFromValidated($validated));
        $this->beforeCreate($model, $validated);
        $model->save();

        return $this->flashSuccess($this->indexRoute(), 'Record created successfully.');
    }

    public function edit(int|string $id): View
    {
        $model = $this->findOrFail($this->modelClass(), $id);

        return view($this->views()['edit'], array_merge(
            [$this->editModelKey() => $model],
            $this->editViewData($model)
        ));
    }

    public function update(Request $request, int|string $id)
    {
        $validated = $this->validated($request);
        $model = $this->findOrFail($this->modelClass(), $id);
        $model->fill($this->attributesFromValidated($validated));
        $this->beforeUpdate($model, $validated);
        $model->save();

        return $this->flashSuccess($this->indexRoute(), 'Record updated successfully.');
    }

    public function delete(Request $request, int|string $id)
    {
        try {
            $model = $this->findOrFail($this->modelClass(), $id);

            $this->crud->delete($model, function (Model $record) {
                $this->beforeDelete($record);
            });

            $params = $request->filled('page') ? ['page' => $request->input('page')] : [];

            return $this->flashSuccess($this->indexRoute(), 'Record deleted successfully.', $params);
        } catch (\Throwable $e) {
            return $this->flashError($this->indexRoute(), 'Failed to delete record: ' . $e->getMessage());
        }
    }

    protected function indexQuery(): Builder
    {
        $relations = $this->indexRelations();

        return $this->modelClass()::query()->when($relations !== [], fn ($q) => $q->with($relations));
    }

    protected function validated(Request $request): array
    {
        $formRequestClass = $this->formRequestClass();

        /** @var FormRequest $formRequest */
        $formRequest = $formRequestClass::createFrom($request);
        $formRequest->setContainer(app());
        $formRequest->setRedirector(app('redirect'));
        $formRequest->validateResolved();

        return $formRequest->validated();
    }
}
