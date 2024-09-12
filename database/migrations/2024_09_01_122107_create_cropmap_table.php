<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cropmap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->references('id')->on('farms')
            ->constrained()->onDelete('cascade');
            $table->foreignId('crop_id')->references('id')->on('crops')
            ->constrained()->onDelete('cascade');
            $table->json('coords');            
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
        Schema::dropIfExists('cropmap');
    }
}
