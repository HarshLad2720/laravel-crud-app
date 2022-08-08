<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tasks', function (Blueprint $table) {
            $table->increments("task_id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("task_name",191)->nullable();
            $table->string("task_description",191)->nullable();
            $table->string("task_image",191)->nullable();
            $table->unsignedInteger('user_id')->nullable()->comment('Users table ID');
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
