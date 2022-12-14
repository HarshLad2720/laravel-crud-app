<?php

namespace App\Models\User;

use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\RoleResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory,SoftDeletes,Scopes,CreatedbyUpdatedby,UploadTrait;

    /**
     * @var array
     */
    protected $fillable = [ 'id', 'name', 'guard_name', 'landing_page' ];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [ 'id', 'name', 'guard_name', 'landing_page' ];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [  ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [ 'id', 'name' ];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [ 'users' ];

    /**
     * @var array
     */
    public $sortable = [ 'roles.created_at', 'roles.id', 'name', 'guard_name', 'landing_page' ];

    /**
     * @var array
     */
    public $foreign_sortable = [  ];

    /**
     * @var array
     */
    public $foreign_table = [  ];

    /**
     * @var array
     */
    public $foreign_key = [  ];

    /**
     * @var array
     */
    public $foreign_method = [  ];

    /**
     * @var array
     */
    public $type_sortable = [  ];

    /**
     * @var array
     */
    public $type_enum = [

    ];

    /**
     * @var array
     */
    public $type_enum_text = [

    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [  ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'string',
        'name'          => 'string',
        'landing_page'  => 'string',
        'guard_name'    => 'string',
        'created_by'    => 'string',
        'updated_by'    => 'string',
        'deleted_by'    => 'string'
    ];

    /**
     * Get the Permissions for the Role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,"permission_role","role_id","permission_id");
    }
}
