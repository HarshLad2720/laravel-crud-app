<?php

namespace App\Imports\User;

use App\Models\User\State;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;

class StatesImport implements ToCollection, WithStartRow
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
            '0' => 'required|numeric|exists:countries,id,deleted_at,NULL',
            '1' => 'required|max:255|unique:states,name,NULL,id,deleted_at,NULL'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required' => trans('Country is required'),
            '1.required' => trans('State is required'),
        ];
    }

    public function validateBulk($collection){
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
                State::create([
                    'country_id' => $col[0],
                    'name'       => $col[1],
                ]);
            }
        }
    }
}
