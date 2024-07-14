<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('farm_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('user_id');
            $table->string('expense_type');
            $table->string('expense_subtype')->nullable();
            $table->date('date');
            $table->json('details');
            $table->decimal('total', 10, 2);
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('farm_expenses');
    }
}
