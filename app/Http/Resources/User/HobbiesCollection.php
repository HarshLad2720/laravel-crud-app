<?php

namespace App\Http\Resources\User;

use App\Http\Resources\DataJsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HobbiesCollection extends DataJsonResponse
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
