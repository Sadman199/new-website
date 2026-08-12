<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesAdminResponses;
use App\Http\Controllers\Admin\Concerns\QueriesAdminRecords;
use Illuminate\Database\Eloquent\Model;

abstract class AdminController extends Controller
{
    use HandlesAdminResponses;
    use QueriesAdminRecords;

    protected function findOrFail(string $modelClass, int|string $id, array $with = []): Model
    {
        $query = $modelClass::query();

        if ($with !== []) {
            $query->with($with);
        }

        return $query->findOrFail($id);
    }
}
