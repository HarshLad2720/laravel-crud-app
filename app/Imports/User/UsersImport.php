<?php
namespace App\Imports\User;

use App\User;
use App\Models\User\UserGallery;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersImport implements ToCollection, WithStartRow
{
    use Scopes;
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
            '0' => 'required | max:255',
            '1' => 'required|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            '2' => 'required |nullable| min:6 | max:255',
            '3' => 'required | digits:10',
            '4' => ['required', Rule::in([0, 1,])],
            '5' => 'required|date|date_format:Y-m-d',
            '6' => 'required|max:500',
            '7' => 'required|integer|exists:countries,id,deleted_at,NULL',
            '8' => 'required|integer|exists:states,id,deleted_at,NULL',
            '9' => 'required|integer|exists:cities,id,deleted_at,NULL',
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required' => trans('Name is required'),
            '1.required' => trans('Email is required'),
            '2.required' => trans('Password is required'),
            '3.required' => trans('Mobile no is required'),
            '4.required' => trans('Gender is required'),
            '5.required' => trans('Dob is required'),
            '6.required' => trans('Address is required'),
            '7.required' => trans('Country id is required'),
            '8.required' => trans('State id is required'),
            '9.required' => trans('City id is required'),
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
                $user =  User::create([
                    'name'          => $col[0],
                    'email'         => $col[1],
                    'password'      => bcrypt($col[2]),
                    'mobile_no'     => $col[3],
                    'gender'        => (string)$col[4],
                    'dob'           => $col[5],
                    'address'       => $col[6],
                    'country_id'    => $col[7],
                    'state_id'      => $col[8],
                    'city_id'       => $col[9],
                    'status'        => config('constants.user.status_code.active'),
                ]);

                /* Insert Profile Images */
                if($col[11]) {
                    $profile_path   = 'user/' . $user->id . '/' . $col[11];
                    $date           = config('constants.calender.date_time');
                    $user->email_verified_at = $date;
                    $user->profile           = $profile_path;
                    $user->save();
                }

                /*Hobby insert-query */
                $hobby = explode(',', $col[10]);
                if($hobby) {
                    $user->hobbies()->attach($hobby); //this executes the insert-query
                }

                /* Insert Gallery Images */
                $gallery = explode(',', $col[12]);
                if($gallery) {
                    foreach ($gallery as $image) {
                        $real_path = 'gallery/' . $user->id . '/' . $image;
                        UserGallery::create([
                            'user_id'   => $user->id,
                            'filename'  => $real_path
                        ]);
                    }
                }
            }
        }
    }
}
