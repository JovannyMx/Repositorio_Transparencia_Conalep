<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Documento;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 1. Comando manual por si quieres limpiar documentos escribiendo: php artisan documentos:limpiar-vencidos
Artisan::command('documentos:limpiar-vencidos', function () {
    $this->call('model:prune', [
        '--model' => [Documento::class],
    ]);
})->purpose('Elimina inmediatamente los documentos cuya fecha de vencimiento ya pasó');

// 2. Tarea programada automática usando el trait Prunable de Documento.php
// Nota: Se cambia a everyMinute() para que en entorno local no tengas que esperar hasta medianoche
Schedule::command('model:prune', [
    '--model' => [Documento::class],
])->everyMinute();