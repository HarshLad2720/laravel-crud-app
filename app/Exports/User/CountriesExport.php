<?php

namespace App\Exports\User;

use App\Models\User\Country;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CountriesExport implements FromCollection, WithHeadings
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
        return  User::commonFunctionMethod(Country::select('id', 'name'), $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return['ID', 'Name'];
    }
}
