<?php

namespace App\Imports\User;

use App\Models\User\Country;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class CountriesImport implements ToCollection, WithStartRow
{
    use Scopes,CreatedbyUpdatedby;
    private $errors = [];

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
            '0' => 'required|max:255|unique:countries,name,NULL,id,deleted_at,NULL'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required' => trans('Country is required'),
        ];
    }

    public function validateBulk($collection)
    {
        $i=1;
        foreach ($collection as $col) {
            $i++;
            $validator = Validator::make($col->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $this->errors[] = $error.' on row '. $i;
                    }
                }
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
                Country::create([
                    'name' => $col[0],
                ]);
            }
        }
    }
}
