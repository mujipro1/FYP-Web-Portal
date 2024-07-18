<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropStatusUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('crop_status_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_id');
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crop_status_updates');
    }
}
