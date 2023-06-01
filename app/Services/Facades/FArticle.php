<?php

namespace App\Services\Facades;

use App\Adapter\ApiAdapter;
use App\Models\Source;
use App\Services\Interfaces\IArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FArticle implements IArticle
{

    public function index(Request $request)
    {
        $data = [];

        $sources = Source::query();
        // will call the adapter depend on selected source
        if ($request->has('sources')) {
            $ids = explode(',', $request->input('sources'));
            $sources = $sources->whereIn('id', $ids);
        }
        $sources = $sources->get();
        foreach ($sources as $source) {
            $adapterClass = 'App\Adapter\\' . $source->adapter;
            $res = new $adapterClass($source->api_key, $source->url);
            $items = $res->prepareData($request);
            if (count($items) > 0) {
                $data[][$source->slug] = $items;
            }
        }

        return array_merge(...$data);
    }
}
