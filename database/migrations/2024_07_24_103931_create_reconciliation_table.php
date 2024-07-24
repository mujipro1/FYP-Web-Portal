<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconciliationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconciliation', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 8, 2);
            $table->boolean('spent')->default(0);
            $table->foreignId('expense_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('farm_expense_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')
            ->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('reconciliation');
    }
}
