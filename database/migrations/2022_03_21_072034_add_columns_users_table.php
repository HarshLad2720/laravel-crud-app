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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_no',255)->nullable();
            $table->string('profile',255)->nullable();
            $table->enum('gender',['0','1'])->nullable()->index()->comment('0 - Female, 1 - Male');
            $table->date('dob')->nullable();
            $table->unsignedInteger('country_id')->index()->nullable()->comment('countries table id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedInteger('state_id')->index()->nullable()->comment('states table id');
            $table->foreign('state_id')->references('id')->on('states');
            $table->unsignedInteger('city_id')->index()->nullable()->comment('cities table id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('address',500)->nullable();
            $table->enum('status', ['0', '1'])->index()->comment('0 - Inactive, 1 - Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
