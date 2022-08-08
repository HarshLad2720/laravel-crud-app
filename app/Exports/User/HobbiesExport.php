<?php

namespace App\Exports\User;

use App\Models\User\Hobby;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HobbiesExport implements FromCollection,WithHeadings
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
        return User::commonFunctionMethod(Hobby::select('id', 'name'), $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return['ID', 'Name'];
    }
}
