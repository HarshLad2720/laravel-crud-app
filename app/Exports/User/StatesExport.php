<?php

namespace App\Exports\User;

use App\Models\User\State;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class StatesExport implements FromCollection, WithHeadings
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
        return  User::commonFunctionMethod(State::select('id',
            DB::raw('(SELECT name from countries WHERE id = states.country_id) AS country_name'), 'name'), $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return['ID', 'Country Name', 'State Name'];
    }
}
