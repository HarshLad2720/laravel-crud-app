<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\RolesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Requests\User\CsvRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\RoleCollection;
use App\Http\Resources\User\RoleResource;
use App\Imports\User\RolesImport;
use App\Imports\User\Rolesmport;
use App\Models\User\Permission;
use App\Models\User\Role;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/*
   |--------------------------------------------------------------------------
   | roles Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the roles of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class RoleAPIController extends Controller
{
    /**
     * list roles
     * @param Request $request
     * @return RoleCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $roles = new Role();
            $query = User::commonFunctionMethod(Role::select($roles->light),$request,true);
            return new RoleCollection(RoleResource::collection($query),RoleResource::class);
        } else{
            $query = User::commonFunctionMethod(Role::class,$request);
        }

        return new RoleCollection(RoleResource::collection($query),RoleResource::class);
    }

     /**
     * Role Detail
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role->load([]));
    }

    /**
     * Add Role
     * @param RoleRequest $request
     * @return RoleResource
     */
    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());
        return User::GetMessage(new RoleResource($role),config('constants.messages.create_success'));
    }

    /**
     * Update Role
     * @param RoleRequest $request
     * @param Role $role
     * @return RoleResource
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $data = $request->all();
        $role->update($data);
        return User::GetMessage(new RoleResource($role), config('constants.messages.update_success'));
    }

    /**
     * Delete Role
     *
     * @param Request $request
     * @param Role $role
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Role $role)
    {
        if($role->id == config('constants.system_role_id'))
            return User::GetError(config('constants.messages.admin_role_delete_error'));
        $role->delete();

        return new DataTrueResource($role,config('constants.messages.delete_success'));
    }

    /**
     * Delete Role multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            if(in_array(config('constants.system_role_id'), $request->id)) {
                return User::GetError(config('constants.messages.admin_role_delete_error'));
            }

            Role::whereIn('id', $request->id)->get()->each(function($role) {
                $role->delete();
            });
            return new DataTrueResource(true,config('constants.messages.delete_success'));
        }
        else{
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    /**
     * Export Role Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new RolesExport($request),'role.csv');
    }

    /**
     * Import bulk
     * @param CsvRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(CsvRequest $request)
    {
        return User::importBulk($request,new Rolesmport(),config('constants.models.role_model'),config('constants.import_dir_path.role_dir_path'));
    }

    /**
     * Role Detail
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionsByRole($id)
    {
        $role          = Role::where("id",$id)->with("permissions")->firstorfail();
        $allPermission = Permission::getPermissions($role);
        return response()->json(["message"=>"","data"=>$allPermission]);
    }
}
