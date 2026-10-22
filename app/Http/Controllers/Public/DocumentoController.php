<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Documento;

class DocumentoController extends Controller
{
    /**
     * Elimina automáticamente los documentos cuya fecha de vencimiento ya pasó.
     */
    private function limpiarVencidos(): void
    {
        Documento::whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now())
            ->get()
            ->each->delete();
    }

    public function preview(string $liga)
    {
        $this->limpiarVencidos();

        $documento = Documento::where('liga_publica', $liga)->firstOrFail();

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
        $this->limpiarVencidos();

        $documento = Documento::where('liga_publica', $liga)->firstOrFail();

        $media = $documento->getFirstMedia('archivo');

        if (!$media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}