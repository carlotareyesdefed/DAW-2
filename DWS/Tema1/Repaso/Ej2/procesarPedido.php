<?php
require_once __DIR__ . '/catalogo.php';
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/../../formulario.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Debes enviar el formulario mediante POST.');
}

$partido = trim((string) ($_POST['partido'] ?? ''));
$pack = trim((string) ($_POST['pack'] ?? ''));
$unidades = validarEntero('unidades', 1, 8);
$transporte = trim((string) ($_POST['transporte'] ?? ''));

if (
    (!in_array($partido, $partidos, true)) ||
    !array_key_exists($pack, $packs) ||
    !array_key_exists($transporte, $transportes) ||
    $unidades === false
) {
    exit('Los datos introducidos son incorrectos');
}

$extrasSeleccionados = $_POST['extras'] ?? [];

if (!is_array($extrasSeleccionados)) {
    exit('Los extras introducidos son incorrectos');
}

foreach ($extrasSeleccionados as $extra) {
    if (!is_string($extra) || !array_key_exists($extra, $extras)) {
        exit('Los extras introducidos son incorrectos');
    }
}
// Eliminar duplicados
$extrasSeleccionados = array_unique($extrasSeleccionados);

// Calcular precio unitario = pack + suma de extras.
$precioPack = $packs[$pack]['precio'];
$sumaExtras = 0;

foreach ($extrasSeleccionados as $extra) {
    $extra = $extras[$extra]['precio'];
    $sumaExtras += $extra;
}

$precioUnitario = $precioPack + $sumaExtras;

//mercancía = precio unitario * unidades; aplicar código si es válido.
$codigo = trim((string) ($_POST['codigo'] ?? ''));

$mercancia = $precioUnitario * $unidades;

$descuento = 0;
$mensajeCodigo = '';

if ($codigo === '') {
    $mensajeCodigo = 'No se ha introducido ningún código.';
} elseif (!array_key_exists($codigo, $codigos)) {
    $mensajeCodigo = 'El código introducido no es válido.';
} else {
    $codigoDescuento = $codigos[$codigo];
    $descuento = $mercancia * ($codigoDescuento / 100);
}

$mercanciaDescontada = $mercancia - $descuento;

// Sumo el transporte a la mercancía
$precioBase = $mercanciaDescontada + $transportes[$transporte];

// Calculo IVA: Precio sin IVA × 0,21
$iva = $precioBase * ($ivaPorcentaje / 100);

// Precio total
$precioTotal = $precioBase + $iva;

//Mensaje si precio total supera los 500€
$mensaje = '';

if ($precioTotal > $presupuestoMaximo) {
    $mensaje = 'El precio total supera los 500 €.';
} else {
    $mensaje = 'El precio total no supera los 500 €.';
}

// Cargamos la vista
require __DIR__ . '/vistas/ticket.php';

