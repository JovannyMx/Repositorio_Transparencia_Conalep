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
    Schema::table('secciones', function (Blueprint $table) {
        $table->boolean('activo')->default(true)->after('orden');
    });
}

public function down(): void
{
    Schema::table('secciones', function (Blueprint $table) {
        $table->dropColumn('activo');
    });
}
};
