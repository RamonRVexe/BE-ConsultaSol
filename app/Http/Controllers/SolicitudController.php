<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class SolicitudController extends Controller
{
    public function obtenerDatosSol(Request $request){
           // Validación para asegurar que al menos uno de los dos parámetros esté presente
           $request->validate([
            'folio' => 'nullable|string|max:20',
            'curp' => 'nullable|string|max:18',
        ]);

        // Validar que al menos uno de los dos parámetros esté presente
        if (!$request->has('folio') && !$request->has('curp')) {
            return response()->json([
                'error' => 'Debes proporcionar un folio o un curp para realizar la consulta.'
            ], 400);
        }

        // Búsqueda por folio o curp
        $solicitudes = Solicitud::query()
            ->when($request->folio, function ($query, $folio) {
                return $query->where('folio', $folio);
            })
            ->when($request->curp, function ($query, $curp) {
                return $query->where('curp', $curp);
            })
            ->get(['idcovocatoria', 'id', 'folio', 'estatussolicitud', 'curp', 'nombre', 'escuela', 'campus', 'nivelacademico', 'carrera', 'ganador', 'motivorechazo', 'comentario', 'comentario2']);

        // Validar si se encontraron resultados
        if ($solicitudes->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron resultados para los criterios de búsqueda.'
            ], 404);
        }

        // Mapeo de idconvocatoria a su nombre
        $solicitudes = $solicitudes->map(function ($solicitud) {

            $solicitud->ganador = $solicitud->ganador == 1.0 ? 1 : 0;

            switch ($solicitud->idcovocatoria) {
                case 12:
                    $solicitud->nombreconvocatoria = "UNIVERSIDADES PÚBLICAS 2DO SEMESTRE 2024";
                    break;
                case 4:
                    $solicitud->nombreconvocatoria = "ESCUELAS PARTICULARES PRESCOLAR, PRIMARIA Y SECUNDARIA 2024 - 2025";
                    break;
                case 6:
                    $solicitud->nombreconvocatoria = "ESCUELAS PARTICULARES NIVEL MEDIO SUPERIOR Y SUPERIOR CICLO 2024 – 2025";
                    break;
                default:
                    $solicitud->nombreconvocatoria = "Convocatoria no identificada";
                    break;
            }
            return $solicitud;
        });



        return response()->json($solicitudes, 200);
    }
}
