<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->unsignedBigInteger('visitas')->default(0)->after('activo');
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('vistas')->default(0)->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn('visitas');
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('vistas');
        });
    }
};