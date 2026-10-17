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
    Schema::table('documentos', function (Blueprint $table) {
        // Agrega el campo después de fecha_publicacion
        $table->date('fecha_vencimiento')->nullable()->after('fecha_publicacion');
    });
}

public function down(): void
{
    Schema::table('documentos', function (Blueprint $table) {
        $table->dropColumn('fecha_vencimiento');
        });
    }
};
