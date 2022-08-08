<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class HobbiesRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|max:255|unique:hobbies,name,NULL,id,deleted_at,NULL',
            ];
        }
        else{
            return [
                'name' => 'required|max:255|unique:hobbies,name,' . $id.',id,deleted_at,NULL',
            ];
        }
    }
}
