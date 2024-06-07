<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'requestType' => $this->requestType->name,
            'users' => [
                'username' => $this->usersData->username,
                'email' => $this->usersData->email,
            ],
            'physician' => [
                'firstName' => $this->provider ? $this->provider->first_name : '',
                'lastName' => $this->provider ? $this->provider->last_name : '',
                'userDetail' => [
                    'username' => $this->provider ? ($this->provider->users ? $this->provider->users->username : '') : '',
                    'email' => $this->provider ? ($this->provider->users ? $this->provider->users->email : '') : '',
                ]
            ],
            'fullName' => $this->first_name . ' ' . $this->last_name,
        ];
    }
}
