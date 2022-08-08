<?php

namespace App\Models\User\Tasks;

use App\Traits\CreatedbyUpdatedby;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory,CreatedbyUpdatedby;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['task_id', 'task_name','user_id','task_description','task_image' ];

    /**
     * @var array
     */
    public $sortable=[ 'task_id', 'task_name','user_id','task_description','task_image' ];

    /**
     * @var array
     */
    public $foreign_sortable = ['id'];

    /**
     * @var array
     */
    public $foreign_table = ['users'];

    /**
     * @var array
     */
    public $foreign_key = ['name'];

    /**
     * @var array
     */
    public $foreign_method = ['userList'];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['task_id', 'task_name'];

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
        'task_id'=>'string',
        'user_id'=>'string',
        'task_name'=>'string',
        'task_description'=>'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userList() {
        return $this->belongsTo(User::class);
    }
}
