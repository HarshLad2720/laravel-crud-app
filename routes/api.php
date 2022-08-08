<?php

/*use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User\UsersAPIController;
use App\Http\Controllers\API\User\LoginAPIController;
use App\Http\Controllers\API\User\CountriesAPIController;
use App\Http\Controllers\API\User\HobbiesAPIController;
use App\Http\Controllers\API\User\ForgotPasswordAPIController;
use App\Http\Controllers\API\User\StatesAPIController;
use App\Http\Controllers\API\User\CitiesAPIController;
use App\Http\Controllers\API\User\VerificationAPIController;
use App\Http\Controllers\API\User\RoleAPIController;
use App\Http\Controllers\API\User\PermissionsAPIController;*/
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Auth::routes(['verify' => true]);

Route::group([
   'prefix'     => 'v1',
],function (){

    Route::group([
        'namespace' => 'User',
    ], function (){

        Route::get('email/verify/{id}', '\App\Http\Controllers\API\User\VerificationAPIController@verify')->name('verification.verify');

        Route::post('forgot-password', '\App\Http\Controllers\API\User\ForgotPasswordAPIController@sendResetLinkResponse');
        Route::post('refresh-token-login', '\App\Http\Controllers\API\User\LoginAPIController@refreshingTokens');

        Route::post('register', '\App\Http\Controllers\API\User\UsersAPIController@register');
        Route::get('batch-request', '\App\Http\Controllers\API\User\UsersAPIController@batchRequest');
        Route::post('login', '\App\Http\Controllers\API\User\LoginAPIController@login');

        Route::get('countries', '\App\Http\Controllers\API\User\CountriesAPIController@index');
        Route::get('states', '\App\Http\Controllers\API\User\StatesAPIController@index');
        Route::get('cities', '\App\Http\Controllers\API\User\CitiesAPIController@index');
        Route::get('hobbies', '\App\Http\Controllers\API\User\HobbiesAPIController@index');
    });

    Route::group([
        'middleware' => ['auth:api','check.permission'],
    ],function (){

        // Role Routes
        Route::apiResource('roles', '\App\Http\Controllers\API\User\RoleAPIController');
        Route::post('roles-delete-multiple', '\App\Http\Controllers\API\User\RoleAPIController@deleteAll');
        Route::get('roles-export', '\App\Http\Controllers\API\User\RoleAPIController@export');
        Route::get('get_role_by_permissions/{id}', '\App\Http\Controllers\API\User\RoleAPIController@getPermissionsByRole');
        Route::post('roles-import-bulk', '\App\Http\Controllers\API\User\RoleAPIController@importBulk');

        //Permissions Routes
        Route::apiResource('permissions', '\App\Http\Controllers\API\User\PermissionsAPIController');
        Route::post('permissions-delete-multiple', '\App\Http\Controllers\API\User\PermissionsAPIController@deleteAll');
        Route::get('permissions-export', '\App\Http\Controllers\API\User\PermissionsAPIController@export');
        Route::post('set_unset_permission_to_role', '\App\Http\Controllers\API\User\PermissionsAPIController@setUnsetPermissionToRole');
        Route::post('permissions-import-bulk', '\App\Http\Controllers\API\User\PermissionsAPIController@importBulk');

        //User Routes
        Route::post('users/{user}', '\App\Http\Controllers\API\User\UsersAPIController@update');
        Route::delete('users-delete/{user}', '\App\Http\Controllers\API\User\UsersAPIController@destory');
        Route::apiResource('users', '\App\Http\Controllers\API\User\UsersAPIController');
        Route::post('users-delete-multiple', '\App\Http\Controllers\API\User\UsersAPIController@deleteAll');
        Route::get('users-export', '\App\Http\Controllers\API\User\UsersAPIController@export');
        Route::post('users-import-bulk', '\App\Http\Controllers\API\User\UsersAPIController@importBulk');

        //Country Routes
        Route::resource('countries','\App\Http\Controllers\API\User\CountriesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('countries-delete-multiple', '\App\Http\Controllers\API\User\CountriesAPIController@deleteAll');
        Route::get('countries-export', '\App\Http\Controllers\API\User\CountriesAPIController@export');
        Route::post('countries-import-bulk', 'App\Http\Controllers\API\User\CountriesAPIController@importBulk');

        //State Routes
        Route::resource('states','\App\Http\Controllers\API\User\StatesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('states-delete-multiple', '\App\Http\Controllers\API\User\StatesAPIController@deleteAll');
        Route::get('states-export', '\App\Http\Controllers\API\User\StatesAPIController@export');
        Route::post('states-import-bulk', '\App\Http\Controllers\API\User\StatesAPIController@importBulk');

        //Cities Routes
        Route::resource('cities','\App\Http\Controllers\API\User\CitiesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('cities-delete-multiple', '\App\Http\Controllers\API\User\CitiesAPIController@deleteAll');
        Route::get('cities-export', 'App\Http\Controllers\API\User\CitiesAPIController@export');
        Route::post('cities-import-bulk', 'App\Http\Controllers\API\User\CitiesAPIController@importBulk');


        //Hobbies Routes
        Route::resource('hobbies','\App\Http\Controllers\API\User\HobbiesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('hobbies-delete-multiple', '\App\Http\Controllers\API\User\HobbiesAPIController@deleteAll');
        Route::get('hobbies-export', '\App\Http\Controllers\API\User\HobbiesAPIController@export');
        Route::post('hobbies-import-bulk', 'App\Http\Controllers\API\User\HobbiesAPIController@importBulk');


        Route::post('change-password', '\App\Http\Controllers\API\User\LoginAPIController@changePassword');

        Route::delete('gallery/{gallery}', '\App\Http\Controllers\API\User\UsersAPIController@delete_gallery');

        Route::post('logout', '\App\Http\Controllers\API\User\LoginAPIController@logout');

        Route::resource('import-csv-log', '\App\Http\Controllers\API\User\ImportCsvLogsAPIController', [
            'only' => ['show', 'index']
        ]);
    });
});


