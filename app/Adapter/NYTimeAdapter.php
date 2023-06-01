<?php

namespace App\Adapter;

use App\Casting\NYTimeCasting\ResponseCasting;
use App\Http\Resources\NYArticleCastingResource;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NYTimeAdapter extends ApiAdapter
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
            $fq = "";
            $q = null;
            $params = [];
            if ($request->has("page")) {
                $page = $request->input('page');
            }
            if ($request->has('source')) {
                $source = $request->input("source");
                $fq .= "source:" . $source;
            }
            if ($request->has('q')) {
                $q = $request->input("q");
            }

            if ($request->has("begin_date")) {
                $begin_date = $request->input("begin_date");
                $params["begin_date"] = date("Ymd", strtotime($begin_date));
            }
            if ($request->has("end_date")) {
                $end_date = $request->input("end_date");
                $params["end_date"] = date("Ymd", strtotime($end_date));
            }

            if ($request->input("section_name")) {
                $section_name = $request->input("section_name");
                if ($fq != "") {
                    $fq .= " And ";
                }
                $fq .= "section_name:" . $section_name;
            } else {
                $categories = $this->categories();
                if ($categories != "") {
                    if ($fq != "") {
                        $fq .= " And ";
                    }
                    $fq .= "section_name:" . $categories;
                }
            }

            $params["page"] = $page;
            $params["fq"] = $fq;

            if ($q) {
                $params["q"] = $q;
            }
            if ($request->has("order_by")) {
                $params["sort"] = $request->input("order_by");
            } else {
                $params["sort"] = "newest";
            }
            $params["api-key"] = $this->_API_KEY;

            $articles = $this->articles($params);
            if ($articles->status() == 200) {
                $obj = ($articles->object());
                $response = $obj->response;

                $data = new ResponseCasting($response->docs);
                return NYArticleCastingResource::dataCollection($data->getResponse())->toArray($request);
            }
            return [];

        } catch (Exception $exception) {
            return [];
        }
    }
}
