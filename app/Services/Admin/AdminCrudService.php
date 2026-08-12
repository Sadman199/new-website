<?php

namespace App\Services\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminCrudService
{
    public function create(string $modelClass, array $attributes): Model
    {
        /** @var Model $model */
        $model = new $modelClass();
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    public function update(Model $model, array $attributes): Model
    {
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    public function delete(Model $model, ?callable $beforeDelete = null): void
    {
        DB::transaction(function () use ($model, $beforeDelete) {
            if ($beforeDelete) {
                $beforeDelete($model);
            }

            $model->delete();
        });
    }
}
