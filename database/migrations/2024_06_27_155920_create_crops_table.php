<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropsTable extends Migration
{
    public function up()
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year');
            $table->integer('acres');
            $table->string('identifier');
            $table->string('variety')->nullable();
            $table->unsignedBigInteger('farm_id');
            $table->date('sow_date');
            $table->date('harvest_date');
            $table->boolean('active')->default(1);
            $table->string('description')->nullable();
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crops');
    }
}
