<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\CitiesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CitiesRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\CitiesCollection;
use App\Http\Resources\User\CitiesResource;
use App\Imports\User\CitiesImport;
use App\Models\User\City;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/*
   |--------------------------------------------------------------------------
   | Cities Controller
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

class CitiesAPIController extends Controller
{
    /**
     * list Cities
     * @param Request $request
     * @return CitiesCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $cities = new City();
            $query = User::commonFunctionMethod(City::select($cities->light),$request,true);
            return new CitiesCollection(CitiesResource::collection($query),CitiesResource::class);
        } else {
            $query = User::commonFunctionMethod(City::class,$request);
        }
        return new CitiesCollection(CitiesResource::collection($query),CitiesResource::class);
    }

    /**
     * City Detail
     * @param City $city
     * @return CitiesResource
     */
    public function show(City $city)
    {
        return new CitiesResource($city->load([]));
    }

    /**
     * add City
     * @param CitiesRequest $request
     * @return CitiesResource
     */
    public function store(CitiesRequest $request)
    {
        return new CitiesResource(City::create($request->all()));
    }

    /**
     * Update City
     * @param CitiesRequest $request
     * @param City $city
     * @return CitiesResource
     */
    public function update(CitiesRequest $request, City $city)
    {
        $city->update($request->all());
        return new CitiesResource($city);
    }

    /**
     * Delete City
     *
     * @param Request $request
     * @param City $city
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Request $request,City $city)
    {
        $city->delete();
        return new DataTrueResource($city);
    }

    /**
     * Delete City multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            City::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Export City Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new CitiesExport($request), 'city.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(Request $request)
    {
        return User::importBulk($request,new CitiesImport(),config('constants.models.city_model'),config('constants.import_dir_path.city_dir_path'));
    }
}
