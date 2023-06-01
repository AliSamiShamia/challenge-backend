<?php

namespace App\Adapter;

use App\Casting\NewsApiCasting\ResponseCasting;
use App\Http\Resources\NewsApiResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsApiAdapter extends ApiAdapter
{
    public function __construct($_API_KEY, $_URL)
    {
        $this->_API_KEY = $_API_KEY;
        $this->_URL = $_URL;
    }

    public function prepareData(Request $request)
    {
        try {
            $page = 1;
            $q = null;
            $params = [];
            if ($request->has("page")) {
                $page = $request->input('page');
            }

            if ($request->has('q')) {
                $q = $request->input("q");
            }

            if ($request->has("begin_date")) {
                $begin_date = $request->input("begin_date");
                $params["from"] = date("Y-m-d", strtotime($begin_date));
            }
            if ($request->has("end_date")) {
                $end_date = $request->input("end_date");
                $params["to"] = date("Y-m-d", strtotime($end_date));
            }

            if ($request->input("section_name")) {
                $q = $request->input('section_name');
            }
            $params["page"] = $page;
            if ($q) {
                $params["q"] = $q;
            }
            $params["apiKey"] = $this->_API_KEY;

            Log::error($params);
            $articles = $this->articles($params);
            Log::error($articles);
            if ($articles->status() == 200) {
                $obj = ($articles->object());
                $response = $obj->response;
                $data = new ResponseCasting($response->articles);
                return NewsApiResource::dataCollection($data->getResponse())->toArray($request);
            }else{
            }
            return [];

        } catch (Exception $exception) {
            return [];
        }
    }
}
