<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Documento;

class DocumentoController extends Controller
{
    public function preview(string $liga)
    {
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
        $documento = Documento::where('liga_publica', $liga)->firstOrFail();

        $media = $documento->getFirstMedia('archivo');

        if (!$media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}