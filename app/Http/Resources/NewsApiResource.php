<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsApiResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->_id,
            "title" => $this->title,
            "date" => date("F j, Y - g:i a", strtotime($this->publishedAt)),
            "publishDate" => strtotime($this->publishedAt),
            "section_name" => $this->section_name,
            "source" => $this->source,
            "image" => $this->urlToImage,
            "web_url" => $this->web_url,
        ];
    }
}
