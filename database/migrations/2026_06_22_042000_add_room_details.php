<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('capacity')->default(1)->after('price');
            $table->integer('slots')->default(1)->after('capacity');
            $table->text('description')->nullable()->after('slots');
            $table->text('facilities')->nullable()->after('description');
            $table->string('photos')->nullable()->after('facilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'slots', 'description', 'facilities', 'photos']);
        });
    }
};