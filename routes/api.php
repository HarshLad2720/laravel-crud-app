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

        //Permissions Routes
        Route::apiResource('permissions', '\App\Http\Controllers\API\User\PermissionsAPIController');
        Route::post('permissions-delete-multiple', '\App\Http\Controllers\API\User\PermissionsAPIController@deleteAll');

        //User Routes
        Route::post('users/{user}', '\App\Http\Controllers\API\User\UsersAPIController@update');
        Route::delete('users-delete/{user}', '\App\Http\Controllers\API\User\UsersAPIController@destory');
        Route::apiResource('users', '\App\Http\Controllers\API\User\UsersAPIController');
        Route::post('users-delete-multiple', '\App\Http\Controllers\API\User\UsersAPIController@deleteAll');

        //Country Routes
        Route::resource('countries','\App\Http\Controllers\API\User\CountriesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('countries-delete-multiple', '\App\Http\Controllers\API\User\CountriesAPIController@deleteAll');

        //State Routes
        Route::resource('states','\App\Http\Controllers\API\User\StatesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('states-delete-multiple', '\App\Http\Controllers\API\User\StatesAPIController@deleteAll');

        //Cities Routes
        Route::resource('cities','\App\Http\Controllers\API\User\CitiesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('cities-delete-multiple', '\App\Http\Controllers\API\User\CitiesAPIController@deleteAll');


        //Hobbies Routes
        Route::resource('hobbies','\App\Http\Controllers\API\User\HobbiesAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);
        Route::post('hobbies-delete-multiple', '\App\Http\Controllers\API\User\HobbiesAPIController@deleteAll');

        //Task Routes
        Route::resource('tasks','\App\Http\Controllers\API\User\TasksAPIController',[
            'only' => ['show', 'store', 'update', 'destroy']
        ]);


        Route::post('change-password', '\App\Http\Controllers\API\User\LoginAPIController@changePassword');

        Route::delete('gallery/{gallery}', '\App\Http\Controllers\API\User\UsersAPIController@delete_gallery');

        Route::post('logout', '\App\Http\Controllers\API\User\LoginAPIController@logout');
    });
});


