<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jugada;
use App\Models\Organizador;
use App\Models\Institucion;
use App\Models\LoteImpresion;
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

        // Lotes ya creados para esta jugada
        $lotes = LoteImpresion::where('jugada_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.jugadas.show', compact('jugada', 'lotes'));
    }

    /**
     * ===============================
     * VISOR COMPLETO DE CARTONES
     * (pantalla profesional de impresión)
     * ===============================
     */
    public function cartones($id)
    {
        $jugada = Jugada::findOrFail($id);
        return view('admin.jugadas.cartones', compact('jugada'));
    }

    /**
     * =====================================================
     * CREAR LOTE DE PRODUCCIÓN (PEDIDO DE IMPRESIÓN)
     * =====================================================
     * Este método:
     * - Recibe cartones u hojas
     * - Calcula equivalencias según formato
     * - Aplica precio por hoja congelado
     * - Aplica costo por generación por cartón
     * - Calcula total general
     * - Genera código único de lote
     * - Deja lote en estado PEDIDO
     * - Cambia estado de la jugada a EN_PRODUCCION
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

        // ===============================
        // Resolver equivalencia
        // ===============================
        if ($request->cantidad_cartones) {
            $cantidadCartones = (int) $request->cantidad_cartones;
            $cantidadHojas = (int) ceil($cantidadCartones / $cartonesPorHoja);
        } else {
            $cantidadHojas = (int) $request->cantidad_hojas;
            $cantidadCartones = $cantidadHojas * $cartonesPorHoja;
        }

        // ===============================
        // Cálculos económicos
        // ===============================
        $totalImpresion = $cantidadHojas * $precioHoja;

        // Costo unitario por cartón
        $costoUnitarioGeneracion = $request->costo_generacion ?? 0;
        $totalGeneracion = $costoUnitarioGeneracion * $cantidadCartones;

        $totalGeneral = $totalImpresion + $totalGeneracion;

        // ===============================
        // Código único del lote
        // ===============================
        $codigoLote = 'L' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        // ===============================
        // Crear lote en base de datos
        // ===============================
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

        // ===============================
        // Cambiar estado de la jugada
        // ===============================
        $jugada->update([
            'estado' => 'en_produccion'
        ]);

        return redirect()
            ->route('admin.jugadas.show', $jugada->id)
            ->with('success', 'Lote creado correctamente. Quedó en estado PEDIDO.');
    }
}
