<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->foreignId('seccion_id')->nullable()->constrained('secciones')->nullOnDelete();
            $table->string('nombre');
            $table->string('archivo_path')->nullable();
            $table->string('extension', 10)->nullable();
            $table->unsignedBigInteger('tamano')->nullable(); // en bytes
            $table->string('liga_publica')->unique();
            $table->integer('orden')->default(0);
            $table->date('fecha_publicacion')->nullable();
            $table->date('fecha_actualizacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};