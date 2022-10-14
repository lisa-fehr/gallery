<?php

namespace LisaFehr\Gallery\Http\Controllers;

use LisaFehr\Gallery\Models\UberTags;
use Illuminate\Support\Facades\Route;

class TagController
{
    public function __invoke($filters = '')
    {
        if (empty($filters)) {
            return response()->json([
                'children' =>
                    UberTags::query()->where('parent', 0)
                    ->get()
                    ->filter(function ($tag) {
                        return Route::has($tag->name);
                    })
            ]);
        }

        $names = explode(',', $filters);
        $currentFilter = $names[0];
        $childFilters = array_slice($names, 1);

        return response()->json([
            'current' => UberTags::query()->where('name', $currentFilter)
                ->with('parent')
                ->first(),
            'children' =>
                UberTags::query()->whereIn('name', $childFilters)
                    //->children()
                    ->with('parent')
                    ->orderBy('name')
                    ->orderBy('parent')
                    ->get()
                    ->filter(function ($tag) use ($childFilters) {
                        $array = $tag->toArray();
                        return Route::has($tag->name) && ($array['parent'] && !in_array($array['parent']['name'], $childFilters));
                    })
        ]);
    }
}
