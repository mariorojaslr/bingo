@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="mb-4 text-white" style="font-family: 'Montserrat', sans-serif; font-weight: 800;">CAJERO MULTIPASARELA</h1>
            <p class="text-white-50 mb-5">Elige tu método de pago preferido. Los recargos financieros se aplican según la plataforma elegida.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('cajero.procesar') }}" method="POST" class="bg-dark p-4 rounded-4 shadow-lg border border-secondary">
                @csrf
                <input type="hidden" name="telefono" value="{{ $participante->telefono }}">
                
                <h4 class="text-white mb-4"><i class="bi bi-wallet2 text-warning"></i> Comprar Fichas Infinity</h4>

                <div class="mb-4">
                    <label class="form-label text-white">1. Selecciona el Paquete</label>
                    <select name="paquete_fichas" class="form-select form-select-lg" required>
                        <option value="500">500 Fichas ($500 Base)</option>
                        <option value="1000">1,000 Fichas ($1,000 Base)</option>
                        <option value="5000">5,000 Fichas ($5,000 Base)</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="form-label text-white">2. Selecciona el Método de Pago</label>
                    
                    <div class="list-group">
                        <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="metodo_pago" value="mp" required>
                                <strong>MercadoPago</strong> (Acreditación Inmediata)
                            </div>
                            <span class="badge bg-danger rounded-pill">+10% Recargo</span>
                        </label>

                        <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="metodo_pago" value="airtm">
                                <strong>Airtm</strong> (Requiere verificación)
                            </div>
                            <span class="badge bg-warning rounded-pill text-dark">+5% Recargo</span>
                        </label>

                        <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="metodo_pago" value="prex_ar">
                                <strong>Prex Argentina</strong> (Transferencia CVU)
                            </div>
                            <span class="badge bg-success rounded-pill">0% Recargo</span>
                        </label>

                        <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="metodo_pago" value="prex_uy">
                                <strong>Prex Uruguay</strong> (Transferencia)
                            </div>
                            <span class="badge bg-success rounded-pill">0% Recargo</span>
                        </label>

                        <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="metodo_pago" value="arq">
                                <strong>ARQ</strong> (Pago Directo)
                            </div>
                            <span class="badge bg-success rounded-pill">0% Recargo</span>
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark">
                        CONTINUAR AL PAGO
                    </button>
                    <a href="{{ route('tienda.show', 1) }}" class="btn btn-outline-light">Volver a la Tienda</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
