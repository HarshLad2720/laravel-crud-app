<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\PermissionsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PermissionsRequest;
use App\Http\Requests\User\SetUnsetPermissionToRoleRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\PermissionsCollection;
use App\Http\Resources\User\PermissionsResource;
use App\Imports\User\PermissionsImport;
use App\Models\User\Permission;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/*
   |--------------------------------------------------------------------------
   | permissions Controller
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

class PermissionsAPIController extends Controller
{
    /**
     * Permissions List
     * @param Request $request
     * @return PermissionsCollection
     * @return PermissionsResource
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $permissions = new Permission();
            $query = User::commonFunctionMethod(Permission::select($permissions->light),$request,true);
            return new PermissionsCollection(PermissionsResource::collection($query),PermissionsResource::class);
        } else {
            $query = User::commonFunctionMethod(Permission::class,$request);
        }
        return new PermissionsCollection(PermissionsResource::collection($query),PermissionsResource::class);
    }

    /**
     * Permission Detail
     * @param Permission $permission
     * @return PermissionsResource
     */
    public function show(Permission $permission)
    {
        return new PermissionsResource($permission);
    }

    /**
     * Create a new Permission instance after a valid Permission.
     * @param PermissionsRequest $request
     * @return PermissionsResource
     */
    public function store(PermissionsRequest $request)
    {
        return new PermissionsResource(Permission::create($request->all()));
    }

    /**
     * Update Permission
     * @param PermissionsRequest $request
     * @param Permission $permission
     * @return PermissionsResource
     */

    public function update(PermissionsRequest $request, Permission $permission)
    {
        $permission->update($request->all());
        return new PermissionsResource($permission);
    }

    /**
     * Delete Role
     * @param Request $request
     * @param Permission $permission
     * @return DataTrueResource
     */

    public function destroy(Request $request, Permission $permission)
    {
        $permission->delete();
        return new DataTrueResource($permission);
    }

    /**
     * Delete Permission multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            Permission::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * This method is used set/unset permission to role
     *
     * @param SetUnsetPermissionToRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUnsetPermissionToRole(SetUnsetPermissionToRoleRequest $request)
    {
        return Permission::setUnsetPermission($request);
    }

    /**
     * Export Roles Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new PermissionsExport($request), 'permission.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(Request $request)
    {
        return User::importBulk($request,new PermissionsImport(),config('constants.models.permission_model'),config('constants.import_dir_path.permission_dir_path'));
    }
}
