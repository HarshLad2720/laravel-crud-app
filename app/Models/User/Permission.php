<?php

namespace App\Models\User;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory,SoftDeletes,CreatedbyUpdatedby,UploadTrait,Scopes;

    /**
     * @var string[]
     */
    public $sortable=[
        'name','guard_name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name','guard_name'
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
    public $light = [ 'id', 'name' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'string',
        'name'       => 'string',
        'guard_name' => 'string',
    ];

    /**
     * Get the Roles for the Permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class,"permission_role","permission_id","role_id");
    }

    /**
     * @param $role
     * @param bool $isSubscription
     * @return array - array of permission
     */
    public static function getPermissions($role,$isSubscription = true)
    {
        $isPermission = $role->permissions->pluck('name')->toArray();// get all role permissions mappings
        $allPermission = [];
        if($isPermission) {
            $rootPermissions = Self::getPermissionByGuardName('root');// get permissions

            if (!$rootPermissions->isEmpty()) {// Check permissions is not empty
                foreach ($rootPermissions As $root) {

                    $root = Self::commonPermissionCode($root, $isPermission, $isSubscription);
                    $firstPermission = [];
                    $firstPermissions = Self::getPermissionByGuardName($root['name']);// get permissions

                    if (!$firstPermissions->isEmpty()) {// Check permissions is not empty
                        foreach ($firstPermissions As $first) {

                            $first = Self::commonPermissionCode($first, $isPermission, $isSubscription);
                            $name = explode("-",$first['name']);
                            $first['name'] = $name[0];
                            $firstPermission[] = $first;
                        }
                    }

                    $root['sub_permissions'] = $firstPermission;
                    $allPermission[] = $root;
                }
            }
        }

        return $allPermission;
    }

    /**
     * This method is used for display name for permission and it's status
     *
     * @param $array
     * @param $isPermission
     * @return mixed
     */
    public static function commonPermissionCode($array,$isPermission,$isSubscription)
    {
        $array['is_permission'] = config('constants.permission.has_not_permission');
        if(in_array($array['name'],$isPermission)){
            $array['is_permission'] = config('constants.permission.has_permission');
        }
        $name = str_replace("-", " ",$array['name']);
        $name = str_replace("and", "&",$name);
        $name = str_replace("slash", "/",$name);
        $array['display_name'] = ucwords($name);
        return $array;
    }

    /**
     * This static method is used to get permissions by guardname
     *
     * @param $guardName
     * @return mixed
     */
    public static function getPermissionByGuardName($guardName)
    {
        return Permission::select('id','name','guard_name')->where('guard_name',$guardName)->orderBy('created_at','asc')->get();
    }

    /**
     * This static method is used to set and unset permission to role
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function setUnsetPermission($request)
    {
        $permissionRole = Permission_role::where('role_id',$request->get('role_id'))->where('permission_id',$request->get('permission_id'))->first();

        if($request->get('is_permission') == "1"){
            if(is_null($permissionRole)){
                Permission_role::insert([
                    'role_id'       => $request->get('role_id'),
                    'permission_id' => $request->get('permission_id'),
                ]);
            }
        }else{
            Permission_role::where('role_id',$request->get('role_id'))->where('permission_id',$request->get('permission_id'))->delete();
        }
        return response()->json(['data' => true]);
    }
}
