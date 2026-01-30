<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Monitor TV Bingo</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    background:#0b0b0b;
    font-family:Segoe UI,Tahoma,sans-serif;
    color:#e8e0b8;
}

/* ================== LAYOUT GENERAL ================== */
.monitor{
    display:grid;
    grid-template-columns: 45% 55%;
    gap:20px;
    padding:20px;
    height:100vh;
}

.col-left,
.col-right{
    display:grid;
    gap:20px;
}

/* ================== IZQUIERDA ================== */
.col-left{
    grid-template-rows: 42% 58%;
}

/* ----- PANEL ----- */
.panel{
    border:4px solid #e8e0b8;
    border-radius:16px;
    padding:16px;
    position:relative;
}

/* ================== BOLILLA PRINCIPAL ================== */
.sorteador{
    display:flex;
    flex-direction:column;
    height:100%;
}

.bolilla-grande{
    margin:auto;
    width:180px;
    height:180px;
    border-radius:50%;
    background:radial-gradient(circle at top left,#00ff9c,#009e6a);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:96px;
    font-weight:900;
    color:#003b26;
    border:6px solid #fff;
    box-shadow:
        inset -10px -10px 20px rgba(0,0,0,.35),
        inset 8px 8px 14px rgba(255,255,255,.25),
        0 14px 28px rgba(0,255,156,.6);
}

/* ----- últimas 9 ----- */
.ultimas{
    margin-top:auto;
    display:grid;
    grid-template-columns:repeat(9,1fr);
    gap:8px;
}

.mini{
    text-align:center;
    padding:8px 0;
    border-radius:8px;
    border:2px solid #444;
    background:#111;
    color:#777;
    font-weight:bold;
}

.mini.activa{
    background:#00ff9c;
    color:#000;
    border-color:#fff;
}

/* ================== TABLERO 1–90 ================== */
.tablero h3{
    text-align:center;
    margin:0 0 10px;
    letter-spacing:2px;
}

.grid90{
    display:grid;
    grid-template-columns:repeat(10,1fr);
    gap:6px;
}

.num{
    padding:6px 0;
    text-align:center;
    border-radius:6px;
    border:1px solid #333;
    background:#111;
    color:#666;
    font-size:13px;
    font-weight:bold;
}

.num.activo{
    background:#00ff9c;
    color:#000;
    border-color:#fff;
    box-shadow:0 0 6px rgba(0,255,156,.6);
}

/* ================== DERECHA ================== */
.col-right{
    grid-template-rows: 52% 48%;
}

/* ----- VIDEO ----- */
.video iframe{
    width:100%;
    height:100%;
    border:none;
}

/* ----- CONTROLES ----- */
.controles{
    position:absolute;
    top:10px;
    right:10px;
    display:flex;
    gap:16px;
    font-size:13px;
}

.switch{
    display:flex;
    align-items:center;
    gap:6px;
}

.switch input{display:none}

.slider{
    width:40px;
    height:18px;
    background:#555;
    border-radius:18px;
    position:relative;
    cursor:pointer;
}

.slider:before{
    content:'';
    width:14px;
    height:14px;
    background:#fff;
    border-radius:50%;
    position:absolute;
    top:2px;
    left:2px;
    transition:.3s;
}

input:checked + .slider{
    background:#00c853;
}
input:checked + .slider:before{
    transform:translateX(20px);
}

/* ----- DATOS ----- */
.datos{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    grid-template-rows:repeat(2,1fr);
    gap:14px;
}

.dato{
    border:2px solid #e8e0b8;
    border-radius:12px;
    text-align:center;
    padding:12px;
}

.dato label{
    font-size:12px;
    opacity:.7;
}

.dato strong{
    display:block;
    font-size:22px;
    margin-top:6px;
}

.vivo{color:#00ff9c}

/* ================== RESPONSIVE ================== */
@media(max-width:1100px){
    .monitor{
        grid-template-columns:1fr;
    }
    .col-left,
    .col-right{
        grid-template-rows:auto;
    }
}
</style>
</head>

<body>

<div class="monitor">

<!-- ================== IZQUIERDA ================== -->
<div class="col-left">

<div class="panel sorteador">
    <div class="bolilla-grande">23</div>

    <div class="ultimas">
        <div class="mini activa">15</div>
        <div class="mini activa">18</div>
        <div class="mini activa">22</div>
        <div class="mini activa">23</div>
        <div class="mini">—</div>
        <div class="mini">—</div>
        <div class="mini">—</div>
        <div class="mini">—</div>
        <div class="mini">—</div>
    </div>
</div>

<div class="panel tablero">
<h3>NÚMEROS 1 AL 90</h3>
<div class="grid90">
<!-- 1 al 90 -->
<?php for($i=1;$i<=90;$i++): ?>
<div class="num <?php if(in_array($i,[3,15,18,22,23])) echo 'activo'; ?>">
<?= $i ?>
</div>
<?php endfor; ?>
</div>
</div>

</div>

<!-- ================== DERECHA ================== -->
<div class="col-right">

<div class="panel video">
<div class="controles">
<label class="switch">VIDEO
<input type="checkbox" id="v" checked><div class="slider"></div>
</label>
<label class="switch">AUDIO
<input type="checkbox" id="a" checked><div class="slider"></div>
</label>
</div>

<iframe id="frame"
src="https://www.youtube.com/embed/F7jzWJEIXmk?autoplay=1&mute=0"
allow="autoplay" allowfullscreen></iframe>
</div>

<div class="panel datos">
<div class="dato"><label>Participantes</label><strong>124</strong></div>
<div class="dato"><label>Cartones</label><strong>356</strong></div>
<div class="dato"><label>Recaudación</label><strong>$178.000</strong></div>
<div class="dato"><label>Estado</label><strong class="vivo">EN VIVO</strong></div>
<div class="dato"><label>Dato</label><strong>—</strong></div>
<div class="dato"><label>Dato</label><strong>—</strong></div>
<div class="dato"><label>Dato</label><strong>—</strong></div>
<div class="dato"><label>Dato</label><strong>—</strong></div>
</div>

</div>
</div>

<script>
const v=document.getElementById('v');
const a=document.getElementById('a');
const f=document.getElementById('frame');
const base="https://www.youtube.com/embed/F7jzWJEIXmk?autoplay=1";

v.onchange=()=>f.src=v.checked?base+"&mute="+(a.checked?0:1):"";
a.onchange=()=>v.checked&&(f.src=base+"&mute="+(a.checked?0:1));
</script>

</body>
</html>
