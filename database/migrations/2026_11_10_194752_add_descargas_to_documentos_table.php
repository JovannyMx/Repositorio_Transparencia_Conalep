<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('descargas')->default(0)->after('vistas');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('descargas');
        });
    }
};