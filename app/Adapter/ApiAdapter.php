<?php

namespace App\Adapter;

use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApiAdapter
{

    public $_URL = "",
        $_API_KEY = "",
        $_param = [];


    public function articles($params, $headers = [])
    {
        return Http::async()->withHeaders($headers)->get($this->_URL, $params)->wait();
    }

    public function categories()
    {
        $user = Auth::guard('api')->check();
//        if ($user) {
//            $categories = UserCategory::query()->where([
//                'user_id' => Auth::guard('api')->id()
//            ])->get()->pluck('category_name')->toArray();
//            return implode(",", $categories);
//        }
        return "";
    }

}
