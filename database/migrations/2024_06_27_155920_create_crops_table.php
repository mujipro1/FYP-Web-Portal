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
            $table->decimal('acres', 8, 2);
            $table->string('identifier');
            $table->string('variety')->nullable();
            $table->date('sow_date');
            $table->date('harvest_date')->nullable();
            $table->boolean('active')->default(1);
            $table->string('description')->nullable();
            $table->string('sugarcane_id')->nullable();
            $table->unsignedBigInteger('farm_id');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crops');
    }
}
