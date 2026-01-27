<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoteImpresion;
use App\Models\JugadaCarton;
use Illuminate\Http\Request;

class VisorController extends Controller
{
    public function verLote(Request $request, $loteId)
    {
        $lote = LoteImpresion::with(['jugada.organizador','jugada.institucion'])->findOrFail($loteId);

        // Estado técnico correcto
        if ($lote->estado !== 'en_impresion') {
            return redirect()
                ->route('admin.jugadas.show', $lote->jugada_id)
                ->with('error', 'Este lote aún no está en impresión.');
        }

        $columnas = (int) $request->get('columnas', 3);
        $filas    = (int) $request->get('filas', 3);
        $porPagina = $columnas * $filas;

        $query = JugadaCarton::with('carton')
            ->where('lote_impresion', $lote->codigo_lote)
            ->orderBy('numero_hoja')
            ->orderBy('posicion_en_hoja');

        // Salto directo a número de cartón
        if ($request->filled('ir_a_carton')) {
            $numero = $request->ir_a_carton;
            $posicion = (clone $query)->whereHas('carton', function($q) use ($numero) {
                $q->where('numero_carton', $numero)
                  ->orWhere('id', $numero);
            })->count();

            $pagina = max(1, ceil($posicion / $porPagina));
        } else {
            $pagina = (int) $request->get('page', 1);
        }

        $cartones = $query
            ->paginate($porPagina, ['*'], 'page', $pagina)
            ->through(function ($jc) {
                return (object)[
                    'numero'   => $jc->carton->numero_carton,
                    'grilla'   => is_string($jc->carton->grilla)
                                    ? json_decode($jc->carton->grilla, true)
                                    : $jc->carton->grilla,
                    'hoja'     => $jc->numero_hoja,
                    'posicion' => $jc->posicion_en_hoja,
                ];
            });

        return view('admin.visor.lote', compact(
            'lote',
            'columnas',
            'filas',
            'cartones'
        ));
    }
}
