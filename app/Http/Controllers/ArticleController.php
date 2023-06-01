<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Services\Interfaces\IArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{

    private IArticle $article;

    public function __construct(IArticle $article)
    {
        $this->article = $article;
    }

    public function index(Request $request)
    {
        $res = $this->article->index($request);
        $res=collect($res)->sortByDesc('publishDate')->reverse()->toArray();
        Log::error($res);
        return BaseResource::create($res);
    }
}
