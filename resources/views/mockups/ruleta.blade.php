<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Ruleta Europea (Móvil)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0c0f; color: #fff; font-family: 'Outfit', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        
        .phone-frame { width: 375px; height: 812px; border-radius: 40px; border: 12px solid #222; overflow: hidden; position: relative; background: #07070a; box-shadow: 0 25px 50px rgba(0,0,0,0.5); display: flex; flex-direction: column; }
        
        /* HEADER */
        .top-bar { padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.5); border-bottom: 1px solid rgba(255,255,255,0.1); z-index: 10;}
        .balance { background: rgba(0,255,136,0.1); border: 1px solid rgba(0,255,136,0.3); color: #00ff88; padding: 5px 15px; border-radius: 20px; font-weight: bold; }

        /* RULETA (RUEDA) */
        .wheel-container { height: 250px; background: radial-gradient(circle at center, #1a1a24 0%, #07070a 100%); display: flex; justify-content: center; align-items: center; position: relative; overflow: hidden; border-bottom: 2px solid #d4af37;}
        .wheel { width: 220px; height: 220px; border-radius: 50%; border: 15px solid #222; background: conic-gradient(
            #e60000 0deg 9.7deg, #111 9.7deg 19.4deg, #e60000 19.4deg 29.1deg, #111 29.1deg 38.8deg,
            #e60000 38.8deg 48.6deg, #111 48.6deg 58.3deg, #e60000 58.3deg 68deg, #111 68deg 77.8deg,
            #e60000 77.8deg 87.5deg, #111 87.5deg 97.2deg, #e60000 97.2deg 107deg, #111 107deg 116.7deg,
            #e60000 116.7deg 126.4deg, #111 126.4deg 136.2deg, #e60000 136.2deg 145.9deg, #111 145.9deg 155.6deg,
            #e60000 155.6deg 165.4deg, #111 165.4deg 175.1deg, #00b33c 175.1deg 184.8deg /* Cero verde */,
            #e60000 184.8deg 194.5deg, #111 194.5deg 204.3deg, #e60000 204.3deg 214deg, #111 214deg 223.7deg,
            #e60000 223.7deg 233.5deg, #111 233.5deg 243.2deg, #e60000 243.2deg 252.9deg, #111 252.9deg 262.7deg,
            #e60000 262.7deg 272.4deg, #111 272.4deg 282.1deg, #e60000 282.1deg 291.8deg, #111 291.8deg 301.6deg,
            #e60000 301.6deg 311.3deg, #111 311.3deg 321deg, #e60000 321deg 330.8deg, #111 330.8deg 340.5deg,
            #e60000 340.5deg 350.2deg, #111 350.2deg 360deg
        ); position: relative; box-shadow: 0 0 30px rgba(0,0,0,0.8) inset, 0 10px 20px rgba(0,0,0,0.5); 
        animation: spin 20s linear infinite; }
        
        .wheel-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 140px; height: 140px; background: #d4af37; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 0 15px rgba(0,0,0,0.5) inset; display: flex; justify-content: center; align-items: center;}
        .wheel-center-inner { width: 30px; height: 30px; background: #222; border-radius: 50%; }
        .ball { position: absolute; width: 12px; height: 12px; background: #fff; border-radius: 50%; box-shadow: 0 0 5px #fff; top: 20px; left: 50%; transform: translateX(-50%); }

        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* PAÑO (TAPETE DE APUESTAS) */
        .board-container { flex: 1; background: #0a4d2e; /* Verde casino oscuro */ padding: 10px; overflow-y: auto; display: flex; flex-direction: column; align-items: center;}
        
        /* Rejilla de números estilo vertical para móvil */
        .board { display: grid; grid-template-columns: 40px repeat(3, 70px) 40px; gap: 4px; margin-top: 10px; }
        .cell { background: transparent; border: 1px solid rgba(255,255,255,0.4); display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 1.2rem; height: 50px; cursor: pointer; transition: 0.2s; position: relative;}
        .cell:hover { background: rgba(255,255,255,0.2); }
        .cell.red { background: #e60000; border-color: #ff3333; }
        .cell.black { background: #111111; border-color: #333; }
        .cell.green { background: #00b33c; border-color: #00ff55; grid-column: 2 / span 3; }
        .cell.action { font-size: 0.8rem; text-transform: uppercase; border-color: rgba(255,255,255,0.2); }
        .cell.col-bet { font-size: 0.8rem; border: 1px dashed rgba(255,255,255,0.5); }
        
        /* Fichas apostadas sobre el paño */
        .chip-placed { position: absolute; width: 24px; height: 24px; background: radial-gradient(circle, #00ff88, #009955); border: 2px dashed #fff; border-radius: 50%; font-size: 0.6rem; color: #000; display: flex; justify-content: center; align-items: center; font-weight: 900; box-shadow: 2px 2px 5px rgba(0,0,0,0.5); z-index: 5;}
        
        /* SECTOR DE FICHAS (BOTTOM BAR) */
        .chip-selector { height: 90px; background: #111; border-top: 1px solid #333; display: flex; justify-content: space-around; align-items: center; padding: 0 10px; }
        .chip { width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: 900; color: #000; cursor: pointer; border: 3px dashed rgba(255,255,255,0.5); box-shadow: 0 5px 10px rgba(0,0,0,0.5); transition: 0.2s;}
        .chip.active { transform: translateY(-10px) scale(1.1); border: 3px solid #fff; box-shadow: 0 0 20px rgba(255,255,255,0.5);}
        .chip-1 { background: radial-gradient(circle, #ddd, #999); }
        .chip-5 { background: radial-gradient(circle, #ff3333, #aa0000); color: #fff;}
        .chip-25 { background: radial-gradient(circle, #00ff88, #009955); }
        .chip-100 { background: radial-gradient(circle, #222, #000); color: #d4af37; border-color: #d4af37;}

        .action-btn { background: #d4af37; color: #000; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 900; font-size: 1.1rem; width: 90%; margin: 10px auto; text-transform: uppercase; box-shadow: 0 5px 15px rgba(212,175,55,0.4); cursor: pointer;}
    </style>
</head>
<body>

    <div class="text-center me-5 d-none d-lg-block">
        <h1 class="fw-bold" style="color: #d4af37;">Ruleta Infinity</h1>
        <p class="text-white-50" style="max-width: 400px;">
            Este mockup demuestra cómo una ruleta compleja puede simplificarse para una pantalla de celular.<br><br>
            La rueda gira en 3D/2D en la parte superior, mientras que el paño de apuestas se optimiza de forma vertical para que los números sean lo suficientemente grandes para tocar con el dedo.
        </p>
    </div>

    <div class="phone-frame">
        
        <!-- HEADER -->
        <div class="top-bar">
            <div><i class="bi bi-list fs-4"></i></div>
            <div class="text-center">
                <div class="small text-white-50" style="font-size: 0.7rem;">RULETA EUROPEA</div>
                <div class="fw-bold">Mesa VIP</div>
            </div>
            <div class="balance">$4.500</div>
        </div>

        <!-- RULETA -->
        <div class="wheel-container">
            <div class="wheel">
                <div class="ball"></div>
                <div class="wheel-center">
                    <div class="wheel-center-inner"></div>
                </div>
            </div>
            <!-- Historico de números que salieron -->
            <div style="position: absolute; right: 10px; top: 10px; display: flex; flex-direction: column; gap: 5px;">
                <span class="badge bg-danger rounded-pill fs-6 border border-light">14</span>
                <span class="badge bg-dark rounded-pill fs-6 border border-light">29</span>
                <span class="badge bg-success rounded-pill fs-6 border border-light">0</span>
            </div>
        </div>

        <!-- PAÑO DE APUESTAS -->
        <div class="board-container">
            <div class="board">
                <!-- Cero -->
                <div></div>
                <div class="cell green">0
                    <div class="chip-placed" style="bottom:-10px; right: 10px;">5</div>
                </div>
                <div></div>

                <!-- Fila 1 -->
                <div class="cell action border-end-0">1-18</div>
                <div class="cell red">1</div>
                <div class="cell black">2</div>
                <div class="cell red">3</div>
                <div class="cell col-bet">2:1</div>
                
                <!-- Fila 2 -->
                <div class="cell action border-end-0" style="border-top:0;">PAR</div>
                <div class="cell black">4</div>
                <div class="cell red">5
                    <div class="chip-placed" style="top:5px; left: 5px;">25</div>
                </div>
                <div class="cell black">6</div>
                <div class="cell col-bet">2:1</div>

                <!-- Fila 3 -->
                <div class="cell action border-end-0" style="background: #e60000;">ROJO</div>
                <div class="cell red">7</div>
                <div class="cell black">8</div>
                <div class="cell red">9</div>
                <div class="cell col-bet">2:1</div>

                <!-- Fila 4 -->
                <div class="cell action border-end-0" style="background: #111;">NEGRO</div>
                <div class="cell black">10</div>
                <div class="cell black">11</div>
                <div class="cell red">12</div>
                <div class="cell action border-bottom-0" style="grid-column: 5; grid-row: 4 / span 3; writing-mode: vertical-rl; transform: rotate(180deg);">1ra Docena</div>

                <!-- Fila 5 -->
                <div class="cell action border-end-0" style="border-top:0;">IMPAR</div>
                <div class="cell black">13</div>
                <div class="cell red">14</div>
                <div class="cell black">15</div>

                <!-- Fila 6 -->
                <div class="cell action border-end-0">19-36</div>
                <div class="cell red">16</div>
                <div class="cell black">17
                    <div class="chip-placed" style="bottom:-10px; right: -10px;">1</div>
                </div>
                <div class="cell red">18</div>

                <!-- ... Resto del tablero se omitió por brevedad gráfica, pero el usuario entiende el concepto vertical -->
                <div style="grid-column: 1 / span 5; text-align: center; color: rgba(255,255,255,0.3); font-size: 0.8rem; margin-top: 10px;">(Deslizar hacia abajo para ver el resto del tapete)</div>
            </div>

            <!-- Boton Girar -->
            <button class="action-btn mt-auto">GIRAR RULETA <i class="bi bi-arrow-clockwise"></i></button>
        </div>

        <!-- FICHAS -->
        <div class="chip-selector">
            <div class="chip chip-1">1</div>
            <div class="chip chip-5">5</div>
            <div class="chip chip-25 active">25</div>
            <div class="chip chip-100">100</div>
            <div class="text-white-50 d-flex flex-column justify-content-center align-items-center" style="cursor: pointer;">
                <i class="bi bi-x-circle fs-4 text-danger"></i>
                <small style="font-size: 0.6rem;">BORRAR</small>
            </div>
        </div>

    </div>

</body>
</html>
