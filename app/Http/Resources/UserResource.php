<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Laravel\Passport\Client as OClient;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'last_login_at' => $this->last_login_at?date('d F Y H:iA',strtotime($this->last_login_at)):"",
            'token' => $this->createToken('API Token')->accessToken,

        ];
    }
}
