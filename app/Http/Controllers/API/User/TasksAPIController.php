<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksAPIController extends Controller
{
    /**
     * List Tasks
     * @param Request $request
     * @return TaskCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $task = new Tasks();
            $query = User::commonFunctionMethod(Tasks::select($task->light),$request,true);
            return new TaskCollection(TaskResource::collection($query),TaskResource::class);
        } else {
            $query = User::commonFunctionMethod(State::class,$request);
        }
        return new TaskCollection(TaskResource::collection($query),TaskResource::class);
    }

    /**
     * Tasks Detail
     * @param Tasks $task
     * @return TaskResource
     */
    public function show(Tasks $task)
    {
        return new TaskResource($task->load([]));
    }

    /**
     * Add Tasks
     * @param TaskRequest $request
     * @return TaskResource
     */
    public function store(TaskRequest $request)
    {
        return new TaskResource(Tasks::create($request->all()));
    }

    /**
     * Update Tasks
     * @param TaskUpdateRequest $request
     * @param Tasks $task
     * @return TaskResource
     */
    public function update(TaskUpdateRequest $request, Tasks $task)
    {
        $task->update($request->all());
        return new TaskResource($task);
    }

    /**
     * Delete State
     *
     * @param Request $request
     * @param Tasks $task
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Request $request, Tasks $task)
    {
        $task->delete();
        return new DataTrueResource($task);
    }

    /**
     * Delete State multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->task_id)) {
            Tasks::whereIn('task_id', $request->task_id)->delete();
            return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

}
