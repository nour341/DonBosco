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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('Total');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('LocalName');
            $table->string('FinancialName');
            $table->string('FinName');
            $table->unsignedBigInteger('center_id');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
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
        Schema::dropIfExists('projects');
    }
};
