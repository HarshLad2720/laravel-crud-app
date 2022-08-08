<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UsersRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\UsersCollection;
use App\Http\Resources\User\UsersResource;
use App\Imports\User\UsersImport;
use App\User;
use App\Models\User\UserGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\UploadTrait;
use Maatwebsite\Excel\Facades\Excel;
use URL;

/*
 |--------------------------------------------------------------------------
 | Users Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles the Roles of
     register,
     index,
     show,
     store,
     update,
     destroy,
     export
 |
 */

class UsersAPIController extends Controller
{
    use UploadTrait;
    /***
     * Register New User
     * @param UsersRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(UsersRequest $request)
    {
        $data             = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['role_id']  = config('constants.role.apply_role');
        $user             = User::create($data); // create new user

        if($request->hasfile('profile')) { // store user profile image in database.
            $path = $this->uploadOne($request->file('profile'), '/user/' . $user->id);
            $user->update(['profile' => $path]);
        }

        if($request->hasfile('gallery')) {  // store user multiple image in pivot table 'user_galleries'
            foreach ($request->gallery as $image) {
                $path = $this->uploadOne($image, '/user/' . $user->id);
                UserGallery::create(['user_id' => $user->id, 'filename' => $path]);
            }
        }

        if($data['hobby']) {
            $user->hobbies()->attach($data['hobby']); //this executes the insert-query
        }

        $user->sendEmailVerificationNotification();
//        return response()->json(['success' => $user]);
        return response()->json(['success' => config('constants.messages.registration_success')], config('constants.validation_codes.ok'));
    }

    /**
     * List All Users
     * @param Request $request
     * @return UsersCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){
            $user = new User();
            $query = User::commonFunctionMethod(User::select($user->light), $request, true);
            return new UsersCollection(UsersResource::collection($query),UsersResource::class);
        } else {
            $query = User::commonFunctionMethod(User::class, $request);
        }
        return new UsersCollection(UsersResource::collection($query),UsersResource::class);

    }

    /**
     * Users detail
     * @param \App\User $user
     * @return UsersResource
     */
    public function show(User $user)
    {
        return new UsersResource($user->load([]));
    }

    /**
     * Update Users
     * @param UsersRequest $request
     * @param \App\User $user
     * @return UsersResource
     */
    public function update(UsersRequest $request, User $user)
    {
        $data = $request->all();
        if($request->hasfile('profile')) { // update user profile image in database.
            $this->deleteOne('/user/' . $user->id . '/' . basename($user->profile));
            $path = $this->uploadOne($request->file('profile'), '/user/' . $user->id);
            $user->update(['profile' => $path]);
        }

        if($request->hasfile('gallery')) { // update user multiple image in pivot table 'user_galleries'
            foreach ($request->gallery as $image) {
                $path = $this->uploadOne($image, '/user/' . $user->id);
                UserGallery::create(['user_id' => $user->id, 'filename' => $path]);
            }
        }

        if($data['hobby']) {
            $user->hobbies()->detach(); //this executes the delete-query
            $user->hobbies()->attach($data['hobby']); //this executes the insert-query
        }

        $user->update($data);
        return new UsersResource($user);
    }

    /**
     * Delete User
     *
     * @param Request $request
     * @param \App\User $user
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destory(Request $request, User $user)
    {
        $user->hobbies()->detach(); //this executes the delete-query

        Storage::deleteDirectory('/user/'.$user->id);
        UserGallery::where('user_id',$user->id)->delete();

        Storage::deleteDirectory('/user/'.$user->id);
        $user->delete();

        return new DataTrueResource($user);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if(!empty($request->id)) {
            User::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        }
        else{
            return response()->json(['error' =>config('constants.messages.delete_multiple_error')], config('constants.validation_codes.422'));
        }
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request), 'user.csv');
    }

    /**
     * Delete gallery
     * @param Request $request
     * @param UserGallery $gallery
     * @return DataTrueResource
     * @throws \Exception
     */
    public function delete_gallery(Request $request, UserGallery $gallery)
    {
        $this->deleteOne('/user/' . $gallery->user_id . '/' . basename($gallery->filename));
        $gallery->delete();

        return new DataTrueResource($gallery);
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(Request $request)
    {
        return User::importBulk($request,new UsersImport(),config('constants.models.user_model'),config('constants.import_dir_path.user_dir_path'));
    }

    /**
     * This is a batch request API
     *
     * @param Request $requestObj
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchRequest(Request $requestObj)
    {
        $requests  = $requestObj->get('request');//get request
        $output = array();
        $cnt = 0;
        foreach ($requests as $request) {// foreach for all requests inside batch

            $request = (object) $request;// array request convert to object

            if($cnt == 10)// limit maximum call 10 requests
                break;

            $url = parse_url($request->url);

            //querystrings code
            $query = array();
            if (isset($url['query'])) {
                parse_str($url['query'], $query);
            }

            $server = ['HTTP_HOST'=> preg_replace('#^https?://#', '', URL::to('/')), 'HTTPS' => 'on'];
            $req = Request::create($request->url, 'GET', $query, [],[], $server);// set request

            $req->headers->set('Accept', 'application/json');//set accept header
            $res = app()->handle($req);//call request

            if (isset($request->request_id)) {// check request_id is set or not
                $output[$request->request_id] = json_decode($res->getContent()); // get response and set into output array
            } else {
                $output[] = $res;
            }

            $cnt++;// request counter
        }

        return response()->json(array('response' => $output));// return batch response
    }
}
