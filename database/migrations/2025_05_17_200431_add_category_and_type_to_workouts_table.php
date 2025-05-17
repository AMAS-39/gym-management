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
    Schema::table('workouts', function (Blueprint $table) {
        $table->foreignId('category_id')->nullable()->constrained('workout_categories')->nullOnDelete();
        $table->foreignId('type_id')->nullable()->constrained('workout_types')->nullOnDelete();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('workouts', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropForeign(['type_id']);
        $table->dropColumn(['category_id', 'type_id']);
    });
}

};
