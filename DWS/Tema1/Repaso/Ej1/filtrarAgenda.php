<?php
require_once __DIR__ . '/datosActos.php';

// TODO 3: recorrer $actos y guardar todos los que cumplen LOS TRES filtros.
// TODO 4: calcular cantidad de coincidencias y aforo total coincidente.
// TODO 5: mostrar solo los primeros $limite actos usando una vista externa.

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    http_response_code(405);
    exit('El formulario debe ser enviado mediante GET.');
}

$provincia = trim((string) ($_GET['provincia'] ?? ''));
$partido = trim((string) ($_GET['partido'] ?? ''));
$tipo = trim((string) ($_GET['tipo'] ?? ''));
$limite = filter_var(
    $_GET['limite'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1, 'max_range' => 10]]
);

if (
    $provincia === '' || !in_array($provincia, $provincias, true) ||
    $partido === '' || !in_array($partido, $partidos, true) ||
    $tipo === '' || !in_array($tipo, $tipos, true) ||
    $limite < 1 || $limite > 10
) {
    exit('Los datos introducidos son incorrectos');
}


$actosGuardados = [];

for ($i = 0; $i < count($actos); $i++) {
    if ($provincia === $actos["provincia"] && $partido === $actos["partido"] && $tipo === $actos["tipo"]) {
        $actosGuardados += $actos[$i];
    }
}
foreach ($actos as $acto) {
    if ($provincia === $acto[$indice]["provincia"] && $partido === $acto[$indice]["partido"] && $tipo === $acto[$indice]["tipo"]) {
        $actosGuardados += $acto[$indice];
        $indice++;
    }
}

$actos = [
    ['fecha' => '2026-11-02', 'hora' => '11:00', 'provincia' => 'Granada', 'partido' => 'PSOE', 'tipo' => 'Mitin', 'lugar' => 'Teatro Norte', 'aforo' => 250],
    ['fecha' => '2026-11-02', 'hora' => '18:00', 'provincia' => 'Sevilla', 'partido' => 'PP', 'tipo' => 'Encuentro', 'lugar' => 'Centro Cívico Sur', 'aforo' => 90],
    ['fecha' => '2026-11-03', 'hora' => '12:30', 'provincia' => 'Málaga', 'partido' => 'Vox', 'tipo' => 'Debate', 'lugar' => 'Salón del Puerto', 'aforo' => 180],
    ['fecha' => '2026-11-03', 'hora' => '19:00', 'provincia' => 'Granada', 'partido' => 'Sumar', 'tipo' => 'Encuentro', 'lugar' => 'Auditorio Sierra', 'aforo' => 120],
    ['fecha' => '2026-11-04', 'hora' => '10:00', 'provincia' => 'Sevilla', 'partido' => 'Podemos', 'tipo' => 'Mitin', 'lugar' => 'Pabellón Este', 'aforo' => 230],
    ['fecha' => '2026-11-04', 'hora' => '17:30', 'provincia' => 'Málaga', 'partido' => 'PSOE', 'tipo' => 'Encuentro', 'lugar' => 'Sala Alameda', 'aforo' => 100],
    ['fecha' => '2026-11-05', 'hora' => '11:00', 'provincia' => 'Granada', 'partido' => 'PP', 'tipo' => 'Debate', 'lugar' => 'Aula Magna', 'aforo' => 200],
    ['fecha' => '2026-11-05', 'hora' => '19:30', 'provincia' => 'Sevilla', 'partido' => 'Vox', 'tipo' => 'Mitin', 'lugar' => 'Teatro Central', 'aforo' => 300],
    ['fecha' => '2026-11-06', 'hora' => '11:30', 'provincia' => 'Málaga', 'partido' => 'Sumar', 'tipo' => 'Debate', 'lugar' => 'Palacio del Mar', 'aforo' => 160],
    ['fecha' => '2026-11-06', 'hora' => '18:00', 'provincia' => 'Granada', 'partido' => 'Podemos', 'tipo' => 'Encuentro', 'lugar' => 'Casa de Cultura', 'aforo' => 80],
    ['fecha' => '2026-11-07', 'hora' => '12:00', 'provincia' => 'Sevilla', 'partido' => 'PSOE', 'tipo' => 'Debate', 'lugar' => 'Salón Guadalquivir', 'aforo' => 170],
    ['fecha' => '2026-11-07', 'hora' => '19:00', 'provincia' => 'Málaga', 'partido' => 'PP', 'tipo' => 'Mitin', 'lugar' => 'Pabellón Oeste', 'aforo' => 320],
    ['fecha' => '2026-11-08', 'hora' => '10:30', 'provincia' => 'Granada', 'partido' => 'Vox', 'tipo' => 'Encuentro', 'lugar' => 'Centro Albaicín', 'aforo' => 70],
    ['fecha' => '2026-11-08', 'hora' => '18:30', 'provincia' => 'Sevilla', 'partido' => 'Sumar', 'tipo' => 'Mitin', 'lugar' => 'Teatro del Río', 'aforo' => 240],
    ['fecha' => '2026-11-09', 'hora' => '11:00', 'provincia' => 'Málaga', 'partido' => 'Podemos', 'tipo' => 'Debate', 'lugar' => 'Salón Litoral', 'aforo' => 140],
    ['fecha' => '2026-11-09', 'hora' => '18:30', 'provincia' => 'Granada', 'partido' => 'PSOE', 'tipo' => 'Debate', 'lugar' => 'Centro Zaidín', 'aforo' => 150],
    ['fecha' => '2026-11-10', 'hora' => '12:00', 'provincia' => 'Sevilla', 'partido' => 'PP', 'tipo' => 'Debate', 'lugar' => 'Sala Plaza', 'aforo' => 170],
    ['fecha' => '2026-11-10', 'hora' => '19:00', 'provincia' => 'Málaga', 'partido' => 'Vox', 'tipo' => 'Encuentro', 'lugar' => 'Centro Costero', 'aforo' => 110]
];

// require __DIR__ . '/vistas/agendaResultado.php';

