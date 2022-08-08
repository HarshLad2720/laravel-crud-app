<?php

namespace App;

use App\Models\User\City;
use App\Models\User\Country;
use App\Models\User\Hobby;
use App\Models\User\Role;
use App\Models\User\State;
use App\Models\User\UserGallery;
use App\Scopes\VerifiedScope;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use Laravel\Passport\HasApiTokens;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Token;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,Scopes,HasApiTokens, UploadTrait,SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'mobile_no', 'profile', 'gender', 'role_id','dob', 'address','country_id','state_id','city_id', 'status' , 'email_verified_at','created_by','updated_by'
    ];

    /**
     * @param $query
     * @param $model
     * @param $request
     * @param $preQuery
     * @param $tablename
     * @param $groupBy
     * @param $export_select
     * @param $no_paginate
     * @return mixed
     */
    public function scopeCommonFunctionMethod($query, $model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        return $this->getCommonFunctionMethod($model, $request, $preQuery, $tablename , $groupBy , $export_select , $no_paginate);
    }

    /**
     * @param $model
     * @param $request
     * @param $preQuery
     * @param $tablename
     * @param $groupBy
     * @param $export_select
     * @param $no_paginate
     * @return mixed
     */
    public static function getCommonFunctionMethod($model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        if (is_null($preQuery)) {
            $mainQuery = $model::withSearch($request->get('search'), $export_select);
        } else {
            $mainQuery = $model->withSearch($request->get('search'), $export_select);
        }
        if($request->filled('filter') != '')
            $mainQuery = $mainQuery->withFilter($request->get('filter'));
        if(!is_null($groupBy))
            $mainQuery = $mainQuery->groupBy($groupBy);
        if ( $no_paginate ){
            return $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select);
        }else{
            return $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select)
                ->withPerPage($request->get('per_page'));
        }
    }

    public $sortable=[
        'id', 'name', 'email', 'mobile_no', 'gender', 'dob', 'address'
    ];

    public $foreign_sortable = [
        'country_id','state_id','city_id'
    ];

    public $foreign_table = [
        'countries','states','cities'
    ];

    public $foreign_key = [
        'name','name','name'
    ];

    public $foreign_method = [
        'country','state','city'
    ];

    /**
    * Lightweight response variable
    *
    * @var array
    */
   public $light = [ 'id', 'name'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['email_verified_at', 'created_at', 'updated_at','deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                    =>'string',
        'name'                  =>'string',
        'email'                 =>'string',
        'password'              =>'string',
        'mobile_no'             =>'string',
        'role_id'               =>'string',
        'profile'               =>'string',
        'gender'                =>'string',
        'dob'                   =>'string',
        'address'               =>'string',
        'country_id'            =>'string',
        'state_id'              =>'string',
        'city_id'               =>'string',
        'status'                =>'string',
        'email_verified_at'     =>'datetime',
        'created_at'            =>'string',
        'updated_at'            =>'string',
        'created_by'            =>'string',
        'updated_by'            =>'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_galleries()
    {
        return $this->hasMany(UserGallery::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class,"hobby_user","user_id","hobby_id");
    }

    /**
     * @param $value
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getProfileAttribute($value){
        if ($value == NULL)
            return "";
        return url(config('constants.image.dir_path') . $value);
    }

    /**
     * Common Display Error Message.
     *
     * @param $query
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetError($message){
        return response()->json(['message' => $message,'errors' => (object)[]], config('constants.validation_codes.unassigned'));
    }

    /**
     *  Common Display Messsage Response.
     *
     * @param $resource
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetMessage($resource, $message){

        return response()->json([
            'message' => $message,
            'data' => $resource,
        ]);

    }

    public static function getUserActiveToken($loginUId, $oauthClientId)
    {
        return Token::where('user_id', $loginUId)->where('client_id', $oauthClientId)->where('revoked', 0)->latest()->first();
    }

    /**
     * Revoking user token
     */
    public static function revoke_token($token)
    {
        $token->update([
            'updated_at' => Carbon::now(),
            'revoked'    => 1
        ]);
    }
}
