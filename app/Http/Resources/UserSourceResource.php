<?php

namespace App\Http\Resources;

use App\Models\UserSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserSourceResource extends BaseResource
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
            "title" => $this->title,
            "slug" => $this->slug,
            "selected" => $this->userSource($this->id)
        ];
    }

    public function userSource($id)
    {
        return (bool)UserSource::query()->where([
            'user_id' => Auth::guard('api')->id(),
            'source_id' => $id
        ])->first();
    }
}
