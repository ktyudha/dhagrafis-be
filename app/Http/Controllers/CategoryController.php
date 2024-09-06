<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __invoke(Request $request)
    {
        $categories = Category::query()
            ->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })->get(['id', 'name', 'slug']);

        return response()->json(['categories' => $categories]);
    }
}
