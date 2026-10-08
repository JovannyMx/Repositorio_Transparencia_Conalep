<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Documento;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tarea programada para eliminar documentos cuya fecha de vencimiento ya pasó
Schedule::call(function () {
    $documentosVencidos = Documento::whereNotNull('fecha_vencimiento')
        ->whereDate('fecha_vencimiento', '<', now())
        ->get();

    foreach ($documentosVencidos as $documento) {
        // Al ejecutar ->delete(), Laravel eliminará el registro
        // y Spatie Media Library se encargará de borrar el archivo físico automáticamente.
        $documento->delete();
    }
})->daily();