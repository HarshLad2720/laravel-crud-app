<?php

namespace App\Exports\User;

use App\Models\User\Role;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RolesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::commonFunctionMethod(Role::select('id','name','guard_name','landing_page'),$this->request, true, null, null, true);
    }

    public function headings():array
    {
        return[
            'Id',
            'Name',
            'Guard name',
            'Landing page'
        ];
    }
}
