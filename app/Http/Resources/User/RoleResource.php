<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;


class RoleResource extends JsonResource
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
            'guard_name'    => $this->guard_name,
            'landing_page'  => $this->landing_page
        ];
    }
}
