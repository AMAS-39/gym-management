<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('workout_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('workout_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained('workout_categories')->onDelete('cascade');
        $table->foreignId('type_id')->constrained('workout_types')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_details');
    }
};
