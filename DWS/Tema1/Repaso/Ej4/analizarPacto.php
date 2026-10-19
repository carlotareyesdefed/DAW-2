<?php
require_once __DIR__ . '/datosEscenarios.php';
require_once __DIR__ . '/funciones.php';

//exigir POST, validar escenario y socios[] (array de 1 a 3 partidos).
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Debes enviar el formulario mediante POST.');
}

$escenario = trim((string) ($_POST['escenario'] ?? ''));
$socios = $_POST['socios'] ?? [];

//rechazar socios repetidos y nombres ajenos a la lista del escenario.
if (
    !array_key_exists($escenario, $escenarios) ||
    !is_array($socios) ||
    count($socios) < 1 ||
    count($socios) > 3
) {
    exit('El escenario o los socios introducidos son incorrectos.');
}

foreach ($socios as $socio) {
    if (
        !is_string($socio) ||
        !array_key_exists($socio, $escenarios[$escenario]['partidos'])
    ) {
        exit('Se ha seleccionado un partido no válido.');
    }
}


if (count($socios) !== count(array_unique($socios))) {
    exit('No puedes seleccionar el mismo partido varias veces.');
}

//sumar escaños y calcular mayoría absoluta (mitad + 1).
$escanosPacto = 0;

// Recorrer los socios seleccionados
foreach ($socios as $socio) {
    $escanosPacto += $escenarios[$escenario]['partidos'][$socio];
}

$totalEscanos = $escenarios[$escenario]['escanos'];
$mayoria = calcularMayoria($totalEscanos);

//indicar mayoría sí/no, y cuántos escaños sobran o faltan.
$mensajeMayoria = '';
$escanosParaMayoria = 0;
$escanosSobrantes = 0;

if ($escanosPacto === $mayoria) {
    $mensajeMayoria = 'Se ha alcanzado la mayoría parlamentaria.';
} elseif ($escanosPacto > $mayoria) {
    $escanosSobrantes = $escanosPacto - $mayoria;
    $mensajeMayoria = 'Se ha alcanzado la mayoría parlamentaria y sobran ' . $escanosSobrantes . ' escaños.';
} else {
    $escanosParaMayoria = $mayoria - $escanosPacto;
    $mensajeMayoria = 'Faltan ' . $escanosParaMayoria . ' para alcanzar la mayoría.';
}

// para cada partido que NO forme parte del pacto, calcular
//          cuántos escaños tendría la coalición SI se uniera.
//          Marcar cuáles de esas coaliciones alcanzarían la mayoría.
$alternativas = [];

foreach ($escenarios[$escenario]['partidos'] as $partido => $escanos) {

    if (!in_array($partido, $socios, true)) {

        $totalCoalicion = $escanosPacto + $escanos;

        $alternativas[$partido] = [
            'escanos' => $escanos,
            'total' => $totalCoalicion,
            'mayoria' => $totalCoalicion >= $mayoria
        ];
    }
}

// indicar qué partido fuera del pacto permitiría llegar a
//          la mayoría aportando la MENOR cantidad de escaños.
//          Si el pacto ya tiene mayoría, no se necesita socio adicional.
//          Si ninguno sirve por sí solo, indicarlo.
$mensajePacto = '';
$socioMinimo = null;
$minimoEscanos = null;

if ($escanosPacto >= $mayoria) {

    $mensajePacto = 'El pacto ya tiene mayoría parlamentaria.';

} else {

    foreach ($alternativas as $partido => $datos) {

        if ($datos['mayoria']) {

            if ($minimoEscanos === null || $datos['escanos'] < $minimoEscanos) {

                $minimoEscanos = $datos['escanos'];
                $socioMinimo = $partido;

            }
        }
    }

    if ($socioMinimo !== null) {

        $mensajePacto = "El socio mínimo es $socioMinimo, que aporta $minimoEscanos escaños.";

    } else {

        $mensajePacto = 'Ningún partido externo permite alcanzar la mayoría por sí solo.';

    }
}
// construir el informe en vistas/informe.php.
require __DIR__ . '/vistas/informe.php';

