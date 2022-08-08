<?php

namespace App\Exports\User;

use App\Models\User\City;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CitiesExport implements FromCollection, WithHeadings
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
        return  User::commonFunctionMethod(City::select('id',
            DB::raw('(SELECT name from states WHERE id = cities.state_id) AS state_name'), 'name'), $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return['ID', 'State Name', 'City Name'];
    }
}
