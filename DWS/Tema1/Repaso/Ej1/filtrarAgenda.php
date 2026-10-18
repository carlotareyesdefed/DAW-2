<?php
require_once __DIR__ . '/datosActos.php';

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
    ($provincia !== "Todas" && !in_array($provincia, $provincias, true)) ||
    ($partido !== "Todos" && !in_array($partido, $partidos, true)) ||
    ($tipo !== "Todos" && !in_array($tipo, $tipos, true)) ||
    $limite < 1 || $limite > 10
) {
    exit('Los datos introducidos son incorrectos');
}

$actosFiltrados = [];

foreach ($actos as $acto) {

    if (
        ($provincia === "Todas" || $acto["provincia"] === $provincia) &&
        ($partido === "Todos" || $acto["partido"] === $partido) &&
        ($tipo === "Todos" || $acto["tipo"] === $tipo)
    ) {
        $actosFiltrados[] = $acto;
    }
}

// Número total de coincidencias
$totalCoincidencias = count($actosFiltrados);

// Suma de aforos
$aforoTotal = 0;

foreach ($actosFiltrados as $acto) {
    $aforoTotal += $acto["aforo"];
}

// Aplicamos el límite
$actosVisibles = array_slice($actosFiltrados, 0, $limite);

// Cargamos la vista
require_once __DIR__ . '/vistas/agendaResultado.php';

