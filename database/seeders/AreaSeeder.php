<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Documento;
use App\Models\Seccion;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear el área
        $area = Area::create([
            'nombre' => 'Armonización Contable',
            'slug' => 'armonizacion-contable',
            'icono' => 'contable.svg',
            'descripcion_corta' => 'Estados financieros e información presupuestal.',
            'orden' => 1,
            'activo' => true,
        ]);

        // 2. Crear la sección raíz: el año
        $anio2026 = Seccion::create([
            'area_id' => $area->id,
            'parent_id' => null,
            'nombre' => '2026 (CONAC)',
            'tipo' => 'anio',
            'orden' => 1,
        ]);

        // 3. Crear un trimestre dentro del año
        $primerTrimestre = Seccion::create([
            'area_id' => $area->id,
            'parent_id' => $anio2026->id,
            'nombre' => 'Primer Trimestre',
            'tipo' => 'periodo',
            'orden' => 1,
        ]);

        // 4. Crear categorías dentro del trimestre
        $contenidoContable = Seccion::create([
            'area_id' => $area->id,
            'parent_id' => $primerTrimestre->id,
            'nombre' => 'Contenido Contable',
            'tipo' => 'categoria',
            'orden' => 1,
        ]);

        $contenidoPresupuestal = Seccion::create([
            'area_id' => $area->id,
            'parent_id' => $primerTrimestre->id,
            'nombre' => 'Contenido Presupuestal',
            'tipo' => 'categoria',
            'orden' => 2,
        ]);

        // 5. Crear documentos dentro de cada categoría
        Documento::create([
            'area_id' => $area->id,
            'seccion_id' => $contenidoContable->id,
            'nombre' => 'Estado de Situación Financiera',
            'archivo_path' => 'documentos/2026/1t/estado_situacion_financiera.pdf',
            'extension' => 'pdf',
            'tamano' => 245000,
            'orden' => 1,
            'fecha_publicacion' => now(),
            'activo' => true,
        ]);

        Documento::create([
            'area_id' => $area->id,
            'seccion_id' => $contenidoContable->id,
            'nombre' => 'Estado de Actividades',
            'archivo_path' => 'documentos/2026/1t/estado_actividades.pdf',
            'extension' => 'pdf',
            'tamano' => 198000,
            'orden' => 2,
            'fecha_publicacion' => now(),
            'activo' => true,
        ]);

        Documento::create([
            'area_id' => $area->id,
            'seccion_id' => $contenidoPresupuestal->id,
            'nombre' => 'Presupuesto de Egresos',
            'archivo_path' => 'documentos/2026/1t/presupuesto_egresos.pdf',
            'extension' => 'pdf',
            'tamano' => 312000,
            'orden' => 1,
            'fecha_publicacion' => now(),
            'activo' => true,
        ]);

        // 6. Documento "suelto" directamente en el área (sin sección), como "Bienes Inmuebles"
        Documento::create([
            'area_id' => $area->id,
            'seccion_id' => null,
            'nombre' => 'Bienes Inmuebles 2026',
            'archivo_path' => 'documentos/2026/bienes_inmuebles.xls',
            'extension' => 'xls',
            'tamano' => 87000,
            'orden' => 1,
            'fecha_publicacion' => now(),
            'activo' => true,
        ]);
    }
}