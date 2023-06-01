<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardiansArticleCastingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->webTitle,
            "date" => date("F j, Y - g:i a", strtotime($this->webPublicationDate)) ?? "",
            "publishDate" => strtotime($this->webPublicationDate) ?? "",
            "section_name" => $this->pillarName ?? "",
            "source" => "Guardians",
            "web_url" => $this->webUrl ?? ""
        ];
    }
}
