<?php

namespace App\Http\Resources\User;

use App\Http\Resources\DataJsonResponse;

class UsersCollection extends DataJsonResponse
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
