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
        Schema::create('tasks', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('project_id'); // المفتاح الخارجي لجدول projects
           $table->string('description');
           $table->date('start_date');
           $table->date('end_date');
           $table->string('status');
           $table->timestamps();
           // تعريف العمود project_id كمفتاح خارجي
           $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
