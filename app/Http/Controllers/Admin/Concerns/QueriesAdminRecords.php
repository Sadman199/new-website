<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait QueriesAdminRecords
{
    /**
     * @param  array<int, string|array{0: string, 1: string}>  $searchColumns
     */
    protected function paginateWithSearch(
        Builder $query,
        Request $request,
        array $searchColumns = [],
        int $perPage = 15,
        string $searchKey = 'q'
    ): LengthAwarePaginator {
        if ($searchColumns !== [] && ($search = trim((string) $request->get($searchKey, ''))) !== '') {
            $query->where(function (Builder $sub) use ($searchColumns, $search) {
                foreach ($searchColumns as $column) {
                    if (is_array($column)) {
                        [$field, $operator] = $column;
                        $sub->orWhere($field, $operator, $operator === 'like' ? '%' . $search . '%' : $search);
                        continue;
                    }

                    $sub->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
