<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('rooms', 'floor')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->unsignedSmallInteger('floor')->default(1)->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rooms', 'floor')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropColumn('floor');
            });
        }
    }
};
