<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class StatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->get('is_light',false)) {
            return array_merge($this->attributesToArray(), $this->relationsToArray());
        }
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'country_id'    => $this->country_id,
            'country'       => $this->country,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
