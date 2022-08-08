<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetUnsetPermissionToRoleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'role_id'           => 'required|exists:roles,id,deleted_at,NULL',
            'permission_id'     => 'required|exists:permissions,id,deleted_at,NULL',
            'is_permission'     => ['required', Rule::in([0,1])],
        ];
    }
}
