<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('secciones', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('secciones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};