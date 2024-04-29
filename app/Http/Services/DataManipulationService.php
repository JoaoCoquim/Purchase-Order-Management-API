<?php

namespace App\Http\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DataManipulationService
{

    public function filterSortAndPaginate(Model $model, Request $request): LengthAwarePaginator
    {
        $query = $model::query();

        //Get column names
        $tableColumns = Schema::getColumnListing($model->getTable());

        //Loop through all columns
        foreach ($tableColumns as $column) {
            //Apply filters if a query parameter for the column is provided
            if ($request->has($column)) {
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }

        //Apply sorting if provided
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        }

        //Apply value for items per page if provided, defaults to 10 if not specified
        $perPage = $request->input('per_page', 10);

        //Paginate the query results
        return $query->paginate($perPage);
    }
}
