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
        // 1. Área de Armonización Contable
        $area = Area::firstOrCreate(
            ['slug' => 'armonizacion-contable'],
            [
                'nombre' => 'Armonización Contable',
                'icono' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'descripcion_corta' => 'Estados financieros e información presupuestal.',
                'orden' => 1,
                'activo' => true,
            ]
        );

        // 2. Las otras 3 áreas con sus íconos SVG de antes
        $otrasAreas = [
            [
                'nombre' => 'Dirección General',
                'slug' => 'direccion-general',
                'icono' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'descripcion_corta' => 'Información sobre acuerdos, planes institucionales y normatividad general.',
                'orden' => 2,
                'activo' => true,
            ],
            [
                'nombre' => 'Dirección Académica',
                'slug' => 'direccion-academica',
                'icono' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                'descripcion_corta' => 'Planes de estudio, convocatorias docentes, oferta educativa y certificación.',
                'orden' => 3,
                'activo' => true,
            ],
            [
                'nombre' => 'Unidad de Transparencia',
                'slug' => 'unidad-de-transparencia',
                'icono' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'descripcion_corta' => 'Solicitudes de acceso a la información, informes de gestión y comités.',
                'orden' => 4,
                'activo' => true,
            ],
        ];

        foreach ($otrasAreas as $areaData) {
            Area::updateOrCreate(['slug' => $areaData['slug']], $areaData);
        }

        // Actualizar el icono de Armonización Contable también
        $area->update([
            'icono' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ]);

        // 3. Crear sección raíz
        $anio2026 = Seccion::firstOrCreate(
            ['area_id' => $area->id, 'nombre' => '2026 (CONAC)'],
            [
                'parent_id' => null,
                'tipo' => 'anio',
                'orden' => 1,
            ]
        );

        // 4. Crear trimestre
        $primerTrimestre = Seccion::firstOrCreate(
            ['area_id' => $area->id, 'parent_id' => $anio2026->id, 'nombre' => 'Primer Trimestre'],
            [
                'tipo' => 'periodo',
                'orden' => 1,
            ]
        );

        // 5. Crear categorías
        $contenidoContable = Seccion::firstOrCreate(
            ['area_id' => $area->id, 'parent_id' => $primerTrimestre->id, 'nombre' => 'Contenido Contable'],
            [
                'tipo' => 'categoria',
                'orden' => 1,
            ]
        );

        $contenidoPresupuestal = Seccion::firstOrCreate(
            ['area_id' => $area->id, 'parent_id' => $primerTrimestre->id, 'nombre' => 'Contenido Presupuestal'],
            [
                'tipo' => 'categoria',
                'orden' => 2,
            ]
        );

        // 6. Crear documentos
        Documento::firstOrCreate(
            ['area_id' => $area->id, 'nombre' => 'Estado de Situación Financiera'],
            [
                'seccion_id' => $contenidoContable->id,
                'archivo_path' => 'documentos/2026/1t/estado_situacion_financiera.pdf',
                'extension' => 'pdf',
                'tamano' => 245000,
                'orden' => 1,
                'fecha_publicacion' => now(),
                'activo' => true,
            ]
        );

        Documento::firstOrCreate(
            ['area_id' => $area->id, 'nombre' => 'Estado de Actividades'],
            [
                'seccion_id' => $contenidoContable->id,
                'archivo_path' => 'documentos/2026/1t/estado_actividades.pdf',
                'extension' => 'pdf',
                'tamano' => 198000,
                'orden' => 2,
                'fecha_publicacion' => now(),
                'activo' => true,
            ]
        );

        Documento::firstOrCreate(
            ['area_id' => $area->id, 'nombre' => 'Presupuesto de Egresos'],
            [
                'seccion_id' => $contenidoPresupuestal->id,
                'archivo_path' => 'documentos/2026/1t/presupuesto_egresos.pdf',
                'extension' => 'pdf',
                'tamano' => 312000,
                'orden' => 1,
                'fecha_publicacion' => now(),
                'activo' => true,
            ]
        );

        // 7. Documento suelto
        Documento::firstOrCreate(
            ['area_id' => $area->id, 'nombre' => 'Bienes Inmuebles 2026'],
            [
                'seccion_id' => null,
                'archivo_path' => 'documentos/2026/bienes_inmuebles.xls',
                'extension' => 'xls',
                'tamano' => 87000,
                'orden' => 1,
                'fecha_publicacion' => now(),
                'activo' => true,
            ]
        );
    }
}