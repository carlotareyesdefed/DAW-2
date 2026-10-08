<?php

// Comprobar que el formulario se ha enviado mediante POST.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405); // Method Not Allowed → Método no permitido.
    exit('Envía el formulario mediante POST.');
}

// Escapar el contenido antes de mostrarlo en HTML.
function mostrar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Poner en mayúscula únicamente la primera letra.
function primeraMayuscula($texto): string
{

    //AlFonso marTIN => Alfonso Martin
    $texto = (string) $texto;
    $texto = mb_strtolower($texto, 'UTF-8');
    return ucwords($texto);
}


// Recoger los datos del formulario.
$nombre = trim((string) ($_POST['nombre'] ?? ''));
$edad = filter_var($_POST['edad'] ?? null, FILTER_VALIDATE_INT);
$altura = filter_var($_POST['altura'] ?? null, FILTER_VALIDATE_INT);
$peso = filter_var($_POST['peso'] ?? null, FILTER_VALIDATE_FLOAT);


// Validar los datos.
if (
    $nombre === '' ||
    mb_strlen($nombre) > 20 ||
    $edad === false ||
    $edad < 0 ||
    $edad > 130 ||
    $altura === false ||
    $altura < 50 ||
    $altura > 300 ||
    $peso === false ||
    $peso < 20 ||
    $peso > 500
) {
    exit('Faltan datos o existe algún valor no válido en el formulario.');
}

// Pasar altura a metros
$alturaMetros = $altura / 100;

//Calcular IMC
$imc = round($peso / ($alturaMetros ** 2), 2);

//Calcular pulsaciones máximas
$pulsacionesMax = 220 - $edad;

// Mostrar el resultado.
echo '<!doctype html>';
echo '<html lang="es">';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<title>IMC</title>';
echo '</head>';
echo '<body>';

echo '<h1> Resultados de '
    . mostrar(primeraMayuscula($nombre))
    . '</h1>';

echo '<p>IMC calculado: ' . mostrar($imc) . '</p>';

echo '<p>Pulsaciones máximas estimadas: ' . mostrar($pulsacionesMax) . ' ppm</p>';

echo '<p>Estos cálculos son ejemplos de programación, no una valoración médica.</p>';

echo '</body>';
echo '</html>';