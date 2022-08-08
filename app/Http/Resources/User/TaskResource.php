<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'task_id'           => $this->task_id,
            'task_name'         => $this->task_name,
            'task_description'  => $this->task_description,
            'user_id'           => $this->user_id,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at
        ];
    }
}
