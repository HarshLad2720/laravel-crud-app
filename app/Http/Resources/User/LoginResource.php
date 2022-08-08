<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'mobile_no'     => $this->mobile_no,
            'profile'       =>$this->profile,
            'gender'        => $this->gender,
            'gender_text'   => config('constants.user.gender.'.$this->gender),
            'dob'           => $this->dob,
            'role'          => $this->role,
            'permissions'   => $this->permissions,
            'country_id'    => $this->country_id,
            'country'       => new CountriesResource($this->country),
            'state_id'      => $this->state_id,
            'state'         => $this->state,
            'city_id'       => $this->city_id,
            'city'          => $this->city,
            'address'       => $this->address,
            'status'        => $this->status,
            'status_text'   => config('constants.user.status.'.$this->status),
            'gallery'       => $this->user_galleries,
            'hobby'         => $this->hobbies,
            'authorization' => $this->authorization,
            'samples_excels' =>array([
                'sample_user'     => url(config('constants.sample_dir_path.sample_users')),
                'sample_city'     => url(config('constants.sample_dir_path.sample_cities')),
                'sample_hobby'    => url(config('constants.sample_dir_path.sample_hobbies')),
                'sample_country'  => url(config('constants.sample_dir_path.sample_countries')),
                'sample_state'    => url(config('constants.sample_dir_path.sample_states'))]),
            'email_verified_at'   => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
