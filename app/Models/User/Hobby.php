<?php

namespace App\Models\User;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use Scopes, CreatedbyUpdatedby;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $sortable=[
        'id','name',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'id', 'name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

      /**
    * Lightweight response variable
    *
    * @var array
    */
   public $light = [ 'id', 'name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
        'id'=>'string',
        'name'=>'string',
    ];
}
