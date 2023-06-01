<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface IArticle
{

    public function index(Request $request);

}
