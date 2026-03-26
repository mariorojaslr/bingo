<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugada;
use App\Models\Carton;
use App\Models\PruebaParticipante;
use App\Models\ParticipanteCartonPrueba;
use Illuminate\Support\Str;

class UserStoreController extends Controller
{
    /**
     * Mostrar la Landing de Compra B2C
     */
    public function showTienda($jugadaId)
    {
        $jugada = Jugada::with('institucion', 'organizador')->findOrFail($jugadaId);
        return view('tienda.compra', compact('jugada'));
    }

    /**
     * Procesar el Ticket de Compra B2C (Generación Automática de Cartones en Demo)
     */
    public function procesarCompra(Request $request, $jugadaId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'cantidad' => 'required|integer|min:1|max:4', // Regla de Juego (Max 4 cartones digital)
        ]);

        $jugada = Jugada::findOrFail($jugadaId);

        // Buscar o crear al usuario por teléfono
        $participante = PruebaParticipante::firstOrCreate(
            ['telefono' => $request->telefono],
            [
                'nombre' => mb_strtoupper($request->nombre),
                'token' => (string) Str::uuid(),
                'codigo_acceso' => strtoupper(Str::random(6)),
            ]
        );

        // Generar la cantidad de cartones 100% digitales On-The-Fly para esta jugada
        for($i = 0; $i < $request->cantidad; $i++) {
            $carton = new Carton();
            $carton->serie = 'DIGITAL-' . date('ym');
            $carton->numero_carton = mt_rand(100000, 999999);
            $carton->formato = 'digital_online';
            $carton->grilla = $this->generarGrillaBingo();
            $carton->estado = 'activo';
            $carton->save();

            // Asignarlo al jugador vinculado a esta jugada
            ParticipanteCartonPrueba::create([
                'participante_id' => $participante->id,
                'jugada_id' => $jugada->id,
                'carton_id' => $carton->id
            ]);
        }

        // Redirigir a la confirmación (Ticket Digital)
        return redirect()->route('tienda.gracias', ['token' => $participante->token, 'j' => $jugada->id]);
    }

    /**
     * Landing de Éxito y Ticket Digital con el Link Mágico
     */
    public function gracias(Request $request, $token)
    {
        $jugadaId = $request->query('j');
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();
        $jugada = Jugada::find($jugadaId);

        // Cartones adquiridos hoy
        $comprados = ParticipanteCartonPrueba::where('participante_id', $participante->id)
            ->where('jugada_id', $jugadaId)
            ->count();

        return view('tienda.gracias', compact('participante', 'jugada', 'comprados'));
    }

    /**
     * Helper Matemático para crear el 3x9 Matrix de Bingo Clásico On-The-Fly
     */
    private function generarGrillaBingo()
    {
        $grilla = [];
        for($r=0; $r<3; $r++) {
            $fila = array_fill(0, 9, 0);
            $indices = (array) array_rand(range(0, 8), 5); // 5 huecos llenos por fila
            foreach($indices as $idx) {
                $min = ($idx == 0) ? 1 : $idx * 10;
                $max = ($idx == 8) ? 90 : ($idx * 10) + 9;
                
                // Evitamos duplicados verticales asegurándonos de probar hasta encontrar uno libre
                do {
                    $numero = mt_rand($min, $max);
                    $duplicado = false;
                    foreach ($grilla as $fila_anterior) {
                        if ($fila_anterior[$idx] === $numero) {
                            $duplicado = true; break;
                        }
                    }
                } while ($duplicado);

                $fila[$idx] = $numero;
            }
            $grilla[] = $fila;
        }
        return $grilla;
    }
}
