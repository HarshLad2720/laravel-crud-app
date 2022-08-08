<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\CountriesExport;
use App\Http\Resources\DataTrueResource;
use App\Imports\User\CountriesImport;
use App\User;
use App\Models\User\Country;
use App\Http\Resources\User\CountriesCollection;
use App\Http\Requests\User\CountriesRequest;
use App\Http\Resources\User\CountriesResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

/*
   |--------------------------------------------------------------------------
   | Countries Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the Roles of
     index,
     show,
     store,
     update,
     destroy
   |
   */

class CountriesAPIController extends Controller
{
    /**
     * list Countires
     * @param Request $request
     * @return CountriesCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $countries  = new Country();
            $query      = User::commonFunctionMethod(Country::select($countries->light),$request,true);
            return new CountriesCollection(CountriesResource::collection($query),CountriesResource::class);
        } else {
            $query = User::commonFunctionMethod(Country::class,$request);
        }
        return new CountriesCollection(CountriesResource::collection($query),CountriesResource::class);
        
    }

    /**
     * Country Detail
     * @param Country $country
     * @return CountriesResource
     */
    public function show(Country $country)
    {
        return new CountriesResource($country->load([]));
    }

    /**
     * Add Country
     * @param CountriesRequest $request
     * @return CountriesResource
     */
    public function store(CountriesRequest $request)
    {
        return new CountriesResource(Country::create($request->all()));
    }

    /**
     * Update Country
     * @param CountriesRequest $request
     * @param Country $country
     * @return CountriesResource
     */
    public function update(CountriesRequest $request, Country $country)
    {
        $country->update($request->all());
        return new CountriesResource($country);
    }

    /**
     * Delete Country
     *
     * @param Request $request
     * @param Country $country
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Request $request, Country $country)
    {
        $country->delete();
        return new DataTrueResource($country);
    }

    /**
     * Delete Country multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            Country::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(Request $request)
    {
        return User::importBulk($request,new CountriesImport(),config('constants.models.country_model'),config('constants.import_dir_path.country_dir_path'));
    }

    /**
     * Export Country Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new CountriesExport($request), 'country.csv');
    }
}
