<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    use Concerns\HasSocialAccounts;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'username' => $this->username,
            'display_name' => $this->display_name,
            'is_enabled' => $this->is_enabled,
            $this->mergeSocialAccounts(true),
        ];
    }
}
