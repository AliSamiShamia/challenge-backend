<?php

namespace App\Adapter;

use App\Casting\GuardiansCasting\ResponseCasting;
use App\Http\Resources\GuardiansArticleCastingResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuardiansAdapter extends ApiAdapter
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
                $params["section"] = $section_name;
            }else {
                $categories = $this->categories();
                if ($categories != "") {
                    $params["section"] = $categories;
                }
            }
            if ($request->has("begin_date")) {
                $begin_date = $request->input("begin_date");
                $params["from-date"] = date("Y-m-d", strtotime($begin_date));
            }
            if ($request->has("end_date")) {
                $end_date = $request->input("end_date");
                $params["to-date"] = date("Y-m-d", strtotime($end_date));
            }

            $params["page"] = $page;
            if ($q) {
                $params["q"] = $q;
            }
            if ($request->has("order_by")) {
                $params["order-by"] = $request->input("order_by");
            } else {
                $params["order-by"] = "newest";
            }
            $params["api-key"] = $this->_API_KEY;
            $articles = $this->articles($params);

            if ($articles->status() == 200) {
                $obj = ($articles->object());
                $response = $obj->response;
                $data = new ResponseCasting($response);
                return GuardiansArticleCastingResource::dataCollection($data->getResponse()?->results)->toArray($request);
            }
            return [];

        } catch (Exception $exception) {
            return [];
        }
    }

}
