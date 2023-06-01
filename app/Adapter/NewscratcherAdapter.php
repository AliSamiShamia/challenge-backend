<?php

namespace App\Adapter;

use App\Casting\NewsCratcherCasting\ResponseCasting;
use App\Http\Resources\NewscratcherResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewscratcherAdapter extends ApiAdapter
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
            if ($request->input("section_name")) {
                $section_name = $request->input("section_name");
                $params["topic"] = $section_name;
            }

            if ($request->has("begin_date")) {
                $begin_date = $request->input("begin_date");
                $params["from"] = date("Y/m/d", strtotime($begin_date));
            }
            if ($request->has("end_date")) {
                $end_date = $request->input("end_date");
                $params["to"] = date("Y/m/d", strtotime($end_date));
            }

            $params["page"] = $page;
            $params["lang"] = "en";
            if ($q) {
                $params["q"] = $q;
            } else {
                $params["q"] = "news";
            }
            $articles = $this->articles($params, [
                "x-api-key" => $this->_API_KEY
            ]);
            if ($articles->status() == 200) {
                $obj = ($articles->object());
                $data = new ResponseCasting($obj);
                return NewscratcherResource::dataCollection($data->getResponse()->articles)->toArray($request);
            }
            return [];

        } catch (Exception $exception) {
            return [];
        }
    }


}
