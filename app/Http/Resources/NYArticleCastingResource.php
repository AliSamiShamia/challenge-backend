<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NYArticleCastingResource extends BaseResource
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
            "title" => $this->abstract,
            "date" => date("F j, Y - g:i a", strtotime($this->pub_date)) ?? "",
            "publishDate" => strtotime($this->pub_date) ?? "",
            "section_name" => $this->section_name ?? "",
            "source" => $this->source ?? "",
            "image" => $this->media($this->multimedia),
            "web_url" => $this->web_url ?? "",
        ];
    }

    public function media($multimedia)
    {
        if (count($multimedia) > 0) {
            return [
                "url" => "https://www.nytimes.com/" . $multimedia[0]->url,
                "width" => $multimedia[0]->width,
                "height" => $multimedia[0]->height,
                "crop_name" => $multimedia[0]->crop_name,
            ];
        }
        return [];
    }
}
