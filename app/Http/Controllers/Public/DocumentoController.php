<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Carbon\Carbon;

class DocumentoController extends Controller
{
    /**
     * Valida que un documento esté activo y no vencido para el consumo público.
     */
    private function obtenerDocumentoVigente(string $liga): Documento
    {
        return Documento::where('liga_publica', $liga)
            ->where('activo', true)
            ->where(function ($query) {
                $query->whereNull('fecha_vencimiento')
                      ->orWhere('fecha_vencimiento', '>=', Carbon::today());
            })
            ->firstOrFail(); // Si fue eliminado (SoftDeletes), está inactivo o vencido, regresa 404
    }

    public function preview(string $liga)
    {
        $documento = $this->obtenerDocumentoVigente($liga);

        $media = $documento->getFirstMedia('archivo');

        if (!$media) {
            abort(404);
        }

        $url = $media->getUrl();
        $extension = strtolower($documento->extension);

        if ($extension === 'pdf') {
            return redirect($url);
        }

        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx'])) {
            $viewerUrl = 'https://view.officeapps.live.com/op/view.aspx?src=' . urlencode($url);
            return redirect($viewerUrl);
        }

        return redirect($url);
    }

    public function descargar(string $liga)
    {
        $documento = $this->obtenerDocumentoVigente($liga);

        $media = $documento->getFirstMedia('archivo');

        if (!$media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}