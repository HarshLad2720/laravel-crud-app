<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\HobbiesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\HobbiesRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\HobbiesCollection;
use App\Http\Resources\User\HobbiesResource;
use App\Imports\User\HobbiesImport;
use App\Models\User\Hobby;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/*
   |--------------------------------------------------------------------------
   | Hobbies Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the Roles of
       index,
       show,
       store,
       update,
       destroy,
       export,
       import
   |
   */

class HobbiesAPIController extends Controller
{
    /**
     * Hobbies List
     * @param Request $request
     * @return HobbiesCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $hobbies = new Hobby();
            $query = User::commonFunctionMethod(Hobby::select($hobbies->light),$request,true);
        } else {
            $query = User::commonFunctionMethod(Hobby::class,$request);
        }
        return new HobbiesCollection(HobbiesResource::collection($query),HobbiesResource::class);
    }

    /**
     * Hobby Detail
     * @param Hobby $hobby
     * @return HobbiesResource
     */
    public function show(Hobby $hobby)
    {
        return new HobbiesResource($hobby->load([]));
    }

    /**
     * Add Hobby
     * @param HobbiesRequest $request
     * @return HobbiesResource
     */
    public function store(HobbiesRequest $request)
    {
        return new HobbiesResource(Hobby::create($request->all()));
    }

    /**
     * Update Hobby
     * @param HobbiesRequest $request
     * @param Hobby $hobby
     * @return HobbiesResource
     */
    public function update(HobbiesRequest $request, Hobby $hobby)
    {
        $hobby->update($request->all());
        return new HobbiesResource($hobby);
    }

    /**
     * Delete Hobby
     *
     * @param Request $request
     * @param Hobby $hobby
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Request $request, Hobby $hobby)
    {
        $hobby->delete();
        return new DataTrueResource($hobby);
    }

    /**
     * Delete Hobby multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            Hobby::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Export Hobbies Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new HobbiesExport($request), 'hobby.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(Request $request)
    {
        return User::importBulk($request,new HobbiesImport(),config('constants.models.hobby_model'),config('constants.import_dir_path.hobby_dir_path'));
    }
}
