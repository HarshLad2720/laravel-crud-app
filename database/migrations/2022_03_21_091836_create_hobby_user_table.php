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
        Schema::create('hobby_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->comment('users table ID');
//            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('hobby_id')->comment('hobbies table ID');
//            $table->foreign('hobby_id')->references('id')->on('hobbies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hobby_user');
    }
};
