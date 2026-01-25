<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoteImpresion;
use App\Models\Carton;
use App\Models\JugadaCarton;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    public function generar($loteId)
    {
        $lote = LoteImpresion::findOrFail($loteId);

        if ($lote->estado !== 'pedido') {
            return back()->with('error', 'Este lote no está en estado PEDIDO.');
        }

        $lote->update(['estado' => 'generado']);

        return back()->with('success', 'Lote marcado como GENERADO.');
    }

    public function materializar($loteId)
    {
        $lote = LoteImpresion::findOrFail($loteId);

        if ($lote->estado !== 'generado') {
            return back()->with('error', 'El lote debe estar en estado GENERADO.');
        }

        $jugada   = $lote->jugada;
        $cantidad = $lote->cantidad_cartones;
        $porHoja  = $jugada->cartones_por_hoja;

        DB::transaction(function () use ($lote, $jugada, $cantidad, $porHoja) {

            $cartones = Carton::whereDoesntHave('jugadas', function ($q) use ($jugada) {
                    $q->where('jugada_id', $jugada->id);
                })
                ->inRandomOrder()
                ->limit($cantidad)
                ->lockForUpdate()
                ->get();

            if ($cartones->count() < $cantidad) {
                throw new \Exception('No hay suficientes cartones disponibles en la piscina.');
            }

            $i = 1;
            foreach ($cartones as $carton) {
                JugadaCarton::create([
                    'jugada_id'       => $jugada->id,
                    'carton_id'       => $carton->id,
                    'lote_impresion'  => $lote->codigo_lote,   // ✔ AHORA SÍ
                    'numero_hoja'     => ceil($i / $porHoja),
                    'posicion_en_hoja'=> (($i - 1) % $porHoja) + 1,
                    'estado'          => 'impreso'
                ]);
                $i++;
            }

            $lote->update([
                'estado' => 'materializado',
                'fecha_impresion' => now()
            ]);
        });

        return redirect()
            ->route('admin.visor.lote', $lote->id)
            ->with('success', 'Lote materializado correctamente.');
    }
}
