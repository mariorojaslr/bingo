<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jugada;
use App\Models\Organizador;
use App\Models\Institucion;
use App\Models\LoteImpresion;
use App\Models\ParticipanteCartonPrueba;
use App\Models\PruebaParticipante;
use App\Models\JugadaCarton;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JugadaController extends Controller
{
    /**
     * ===============================
     * LISTADO GENERAL DE JUGADAS
     * ===============================
     */
    public function index()
    {
        $jugadas = Jugada::with(['organizador', 'institucion'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.jugadas.index', compact('jugadas'));
    }

    /**
     * ===============================
     * FORMULARIO CREAR JUGADA
     * ===============================
     */
    public function create()
    {
        $organizadores = Organizador::where('activo', 1)->get();
        $instituciones = Institucion::where('activa', 1)->get();

        return view('admin.jugadas.create', compact('organizadores', 'instituciones'));
    }

    /**
     * ===============================
     * GUARDAR NUEVA JUGADA
     * ===============================
     */
    public function store(Request $request)
    {
        $request->validate([
            'organizador_id'    => 'required|exists:organizadores,id',
            'institucion_id'    => 'required|exists:instituciones,id',
            'nombre_jugada'     => 'required|string|max:255',
            'fecha_evento'      => 'required|date',
            'hora_evento'       => 'nullable',
            'lugar'             => 'nullable|string|max:255',
            'cartones_por_hoja' => 'required|in:3,6',
            'precio_hoja'       => 'required|numeric|min:0'
        ]);

        Jugada::create([
            'organizador_id'    => $request->organizador_id,
            'institucion_id'    => $request->institucion_id,
            'nombre_jugada'     => $request->nombre_jugada,
            'fecha_evento'      => $request->fecha_evento,
            'hora_evento'       => $request->hora_evento,
            'lugar'             => $request->lugar,
            'cartones_por_hoja' => $request->cartones_por_hoja,
            'precio_hoja'       => $request->precio_hoja,
            'estado'            => 'creada',
        ]);

        return redirect()->route('admin.jugadas.index')
            ->with('success', 'Jugada creada correctamente.');
    }

    /**
     * ===============================
     * DETALLE DE JUGADA
     * ===============================
     */
    public function show($id)
    {
        $jugada = Jugada::with(['organizador', 'institucion'])->findOrFail($id);

        $lotes = LoteImpresion::where('jugada_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.jugadas.show', compact('jugada', 'lotes'));
    }

    /**
     * ===============================
     * VISOR COMPLETO DE CARTONES
     * ===============================
     */
    public function cartones($id)
    {
        $jugada = Jugada::findOrFail($id);
        return view('admin.jugadas.cartones', compact('jugada'));
    }

    /**
     * =====================================================
     * CREAR LOTE DE PRODUCCIÓN
     * =====================================================
     */
    public function crearLote(Request $request, $jugadaId)
    {
        $jugada = Jugada::findOrFail($jugadaId);

        $request->validate([
            'cantidad_cartones' => 'nullable|integer|min:1',
            'cantidad_hojas'    => 'nullable|integer|min:1',
            'costo_generacion'  => 'nullable|numeric|min:0'
        ]);

        $cartonesPorHoja = $jugada->cartones_por_hoja;
        $precioHoja      = $jugada->precio_hoja;

        if ($request->cantidad_cartones) {
            $cantidadCartones = (int) $request->cantidad_cartones;
            $cantidadHojas = (int) ceil($cantidadCartones / $cartonesPorHoja);
        } else {
            $cantidadHojas = (int) $request->cantidad_hojas;
            $cantidadCartones = $cantidadHojas * $cartonesPorHoja;
        }

        $totalImpresion = $cantidadHojas * $precioHoja;

        $costoUnitarioGeneracion = $request->costo_generacion ?? 0;
        $totalGeneracion = $costoUnitarioGeneracion * $cantidadCartones;

        $totalGeneral = $totalImpresion + $totalGeneracion;

        $codigoLote = 'L' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        LoteImpresion::create([
            'jugada_id'          => $jugada->id,
            'codigo_lote'        => $codigoLote,
            'cantidad_cartones' => $cantidadCartones,
            'cantidad_hojas'    => $cantidadHojas,
            'precio_hoja'       => $precioHoja,
            'total_impresion'   => $totalImpresion,
            'costo_generacion'  => $totalGeneracion,
            'total_general'     => $totalGeneral,
            'estado'            => 'pedido'
        ]);

        $jugada->update([
            'estado' => 'en_produccion'
        ]);

        return redirect()
            ->route('admin.jugadas.show', $jugada->id)
            ->with('success', 'Lote creado correctamente. Quedó en estado PEDIDO.');
    }

    /**
     * =====================================================
     * ASIGNACIÓN MASIVA DE CARTONES A PARTICIPANTES
     * (N por cada uno, aleatorios, sin repetir)
     * =====================================================
     */
    public function asignarCartonesMasivo(Request $request, Jugada $jugada)
    {
        $request->validate([
            'cantidad_por_participante' => 'required|integer|min:1',
        ]);

        $cantidad = (int) $request->cantidad_por_participante;

        DB::transaction(function () use ($jugada, $cantidad) {

            $participantes = PruebaParticipante::all();

            $cartonesDisponibles = JugadaCarton::where('jugada_id', $jugada->id)
                ->whereNotIn('carton_id', function ($q) use ($jugada) {
                    $q->select('carton_id')
                      ->from('participante_carton_prueba')
                      ->where('jugada_id', $jugada->id);
                })
                ->inRandomOrder()
                ->lockForUpdate()
                ->get();

            $necesarios = $participantes->count() * $cantidad;

            if ($cartonesDisponibles->count() < $necesarios) {
                throw new \Exception("No hay suficientes cartones para asignar {$cantidad} a cada participante.");
            }

            $indice = 0;

            foreach ($participantes as $participante) {
                for ($i = 0; $i < $cantidad; $i++) {

                    $carton = $cartonesDisponibles[$indice];

                    ParticipanteCartonPrueba::create([
                        'participante_prueba_id' => $participante->id,
                        'jugada_id'             => $jugada->id,
                        'carton_id'             => $carton->carton_id,
                    ]);

                    $indice++;
                }
            }
        });

        return redirect()
            ->route('admin.jugadas.show', $jugada->id)
            ->with('success', 'Cartones asignados automáticamente a todos los participantes.');
    }
}
