<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            DB::table('permissions')->insert(array(

                array('name' => 'my-users','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'index-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'importBulk-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-countries','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'store-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'index-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'importBulk-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-states','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'store-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'index-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'importBulk-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-cities','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'store-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'index-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'importBulk-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-hobbies','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'store-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'index-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'importBulk-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-roles','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'store-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'index-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'getPermissionsByRole-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-permissions','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'store-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'index-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'update-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'destroy-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'export-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'setUnsetPermissionToRole-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'delete_gallery-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-login','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'changePassword-logincontroller','guard_name' => 'my-login','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'logout-logincontroller','guard_name' => 'my-login','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'deleteAll-users','guard_name' => 'my-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'deleteAll-countries','guard_name' => 'my-countries','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'deleteAll-states','guard_name' => 'my-states','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'deleteAll-cities','guard_name' => 'my-cities','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'deleteAll-roles','guard_name' => 'my-roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'deleteAll-hobbies','guard_name' => 'my-hobbies','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'deleteAll-permissions','guard_name' => 'my-permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'my-importcsvlogs','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

                array('name' => 'index-importcsvlogs','guard_name' => 'my-importcsvlogs','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
                array('name' => 'show-importcsvlogs','guard_name' => 'my-importcsvlogs','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            ));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
};
