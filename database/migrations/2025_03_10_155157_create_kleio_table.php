<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kleio', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('recommendation')->nullable(); // Nullable recommendation text
            $table->text('fun_fact')->nullable(); // Nullable fun fact text
            $table->unsignedBigInteger('farm_id');
            $table->date('record_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kleio');
    }
};
