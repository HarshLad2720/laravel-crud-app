<?php

namespace App\Imports\User;

use App\Models\User\Permission;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PermissionsImport implements ToCollection, WithStartRow
{
    use Scopes,CreatedbyUpdatedby;
    private $errors = [];
    private $rows = 0;

    public function startRow(): int
    {
        return 2;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    public function rules(): array
    {
        return [
            '0'       => 'required|max:191|unique:permissions,name,NULL,id,deleted_at,NULL',
            '1'       => 'nullable|max:191',
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required'    =>trans('The name is required.'),
            '0.unique'      =>trans('The name has already been taken.'),
            '1.max'         =>trans('The guard_name may not be greater than 191 characters.')
        ];
    }

    public function validateBulk($collection){
        $i=1;
        foreach ($collection as $col) {
            $i++;
            $errors[$i] = ['row' => $i];

            $validator = Validator::make($col->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $errors[$i]['error'][] = $error;
                    }
                }

                $this->errors[] = (object) $errors[$i];
            }

        }
        return $this->getErrors();
    }

    public function collection(Collection $collection)
    {
        $error = $this->validateBulk($collection);
        if($error){
            return;
        }else {
            foreach ($collection as $col) {
                Permission::create([
                    'name'          => $col[0],
                    'guard_name'    => $col[1],
                ]);
                $this->rows++;
            }
        }
    }
}
