<?php

namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $uri    = $request->path();
        $urlArr = explode("/",$uri);
        $id     = end($urlArr);

        $commonRule = [
            'name'          => 'required| max:255',
            'mobile_no'     => 'required',
            'gender'        => ['required', Rule::in([0, 1])],
            'dob'           => 'required|date|date_format:Y-m-d',
            'address'       => 'required|max:500',
            'country_id'    => 'required|integer|exists:countries,id,deleted_at,NULL',
            'state_id'      => 'required|integer|exists:states,id,deleted_at,NULL',
            'city_id'       => 'required|integer|exists:cities,id,deleted_at,NULL',
            'hobby'         => 'required|exists:hobbies,id,deleted_at,NULL|array',
            'hobby.*'       => 'required|integer',
        ];

        if($uri == 'api/v1/register'){
            $commonRule['email']        = 'required|max:255|unique:users,email,NULL,id,deleted_at,NULL';
            $commonRule['password']     = 'required|min:8|nullable|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
            $commonRule['profile']      = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096';
            $commonRule['gallery']      = 'required|array';
            $commonRule['gallery.*']    = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096';

        }else{
            $commonRule['email']        = 'required|max:255|unique:users,email,' . $id.',id,deleted_at,NULL';
            $commonRule['profile']      = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096';
            $commonRule['gallery']      = 'nullable|array';
            $commonRule['gallery.*']    = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096';
        }

        return $commonRule;
    }
}
