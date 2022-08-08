<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\StatesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StatesRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\StatesCollection;
use App\Http\Resources\User\StatesResource;
use App\Imports\User\StatesImport;
use App\Models\User\State;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/*
   |--------------------------------------------------------------------------
   | States Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the Roles of
       index,
       show,
       store,
       update,
       destroy,
       export
   |
   */

class StatesAPIController extends Controller
{
    /**
     * List States
     * @param Request $request
     * @return StatesCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $states = new State();
            $query = User::commonFunctionMethod(State::select($states->light),$request,true);
            return new StatesCollection(StatesResource::collection($query),StatesResource::class);
        } else {
            $query = User::commonFunctionMethod(State::class,$request);
        }
        return new StatesCollection(StatesResource::collection($query),StatesResource::class);
    }

    /**
     * States Detail
     * @param State $state
     * @return StatesResource
     */
    public function show(State $state)
    {
        return new StatesResource($state->load([]));
    }

    /**
     * Add State
     * @param StatesRequest $request
     * @return StatesResource
     */
    public function store(StatesRequest $request)
    {
        return new StatesResource(State::create($request->all()));
    }

    /**
     * Update State
     * @param StatesRequest $request
     * @param State $state
     * @return StatesResource
     */
    public function update(StatesRequest $request, State $state)
    {
        $state->update($request->all());
        return new StatesResource($state);
    }

    /**
     * Delete State
     *
     * @param Request $request
     * @param State $state
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Request $request, State $state)
    {
        $state->delete();
        return new DataTrueResource($state);
    }

    /**
     * Delete State multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            State::whereIn('id', $request->id)->delete();
            // return response()->json(['success' =>config('constants.messages.delete_sucess')]);
           return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Export Country Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new StatesExport($request), 'state.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(Request $request)
    {
        return User::importBulk($request,new StatesImport(),config('constants.models.state_model'),config('constants.import_dir_path.state_dir_path'));
    }
}
