<?php
require_once __DIR__ . '/datosPartidos.php';
require_once __DIR__ . '/../../formulario.php';

// aceptar POST y validar censo entero entre 1 y 100000.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Debes enviar el formulario mediante POST.');
}

$censo = validarEntero("censo", 1, 100000);

if ($censo === false) {
    exit("El censo debe ser un número entero del 1 al 100000.");
}
// validar que votos[] contiene exactamente las claves de $partidos y que todos los votos, blancos y nulos son enteros >= 0.

$votosPartido = $_POST["votos"] ?? [];
$votosBlanco = validarEntero("blancos", 0);
$votosNulos = validarEntero("nulos", 0);

if (
    !is_array($votosPartido) ||
    $votosPartido === [] ||
    $votosBlanco === false ||
    $votosNulos === false
) {
    exit("Los datos de los votos introducidos son incorrectos");
}

// Comprobamos que estén los cinco partidos
if (count($votosPartido) !== count($partidos)) {
    exit('El número de partidos es incorrecto.');
}

// Validamos las claves y los votos
foreach ($votosPartido as $partido => $voto) {

    if (
        !in_array($partido, $partidos, true) ||
        validarValorEntero($voto, 0) === false
    ) {
        exit('Los votos de los partidos son incorrectos.');
    }
}
// votos a candidaturas
$candidaturas = array_sum($votosPartido);

// votos válidos (candidaturas + blancos)
$votosValidos = $candidaturas + $votosBlanco;

// emitidos (válidos + nulos)
$votosEmitidos = $votosValidos + $votosNulos;


// Rechazar votos emitidos > censo y recuentos de 0 emitidos.
if ($votosEmitidos > $censo) {
    exit('Los votos emitidos no pueden ser superiores al censo electoral.');
}

if ($votosEmitidos < 1) {
    exit('Debe haber al menos un voto a candidaturas');
}

// abstención = censo - emitidos
$abstencion = $censo - $votosEmitidos;

// participación = emitidos × 100 / censo
$participacion = $votosEmitidos * 100 / $censo;

// calcular porcentajes de cada partido sobre VOTOS VÁLIDOS
$porcentajeVotos = [];

foreach ($votosPartido as $partido => $voto) {
    $porcentaje = ($voto / $votosValidos) * 100;
    $porcentajeVotos[$partido] = $porcentaje;
}

// Ordenar de mayor a menor
arsort($votosPartido);

// Obtener el porcentaje más alto
$votosMaximos = reset($votosPartido);

// Array para guardar los partidos empatados
$partidosEmpatados = [];

// Buscar los partidos que ocupan el primer puesto
foreach ($votosPartido as $partido => $voto) {

    if ($voto === $votosMaximos) {
        $partidosEmpatados[] = $partido;
    }
}
// Contar cuántos partidos alcanzan el umbral didáctico del 5 %.
$umbral5 = 0;

foreach ($porcentajeVotos as $porcentaje) {
    if ($porcentaje >= 5) {
        $umbral5++;
    }
}

// mostrar tabla HTML mediante vistas/resultados.php.
require __DIR__ . '/vistas/resultados.php';

