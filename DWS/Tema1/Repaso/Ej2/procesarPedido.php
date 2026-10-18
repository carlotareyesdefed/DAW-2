<?php
require_once __DIR__ . '/catalogo.php';
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/../../formulario.php';

// TODO 1: aceptar solo POST y validar partido, pack, transporte, unidades (1..8).
// TODO 2: recuperar extras[] (puede no existir), validar sus valores y eliminar duplicados.
// TODO 3: calcular precio unitario = pack + suma de extras.
// TODO 4: mercancía = precio unitario * unidades; aplicar código si es válido.
// Un código inválido NO da descuento; el transporte NO se descuenta.
// TODO 5: calcular base con transporte, IVA 21 % y total final.
// TODO 6: indicar si total final supera 500 €, y preparar desglose en la vista.


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
    if (!array_key_exists($extra, $extras)) {
        exit('Los extras introducidos son incorrectos');
    }
}

// Eliminar duplicados
$extrasSeleccionados = array_unique($extrasSeleccionados);

// Calcular precio unitario = pack + suma de extras.
$precioPack = $packs[$pack]['precio'];

foreach ($extrasSeleccionados as $extra) {
    $extra = $extras[$extra]['precio'];
    $sumaExtras += $extra;
}

$precioUnitario = $precioPack + $sumaExtras;

//mercancía = precio unitario * unidades; aplicar código si es válido.
$codigo = trim((string) ($_POST['codigo'] ?? ''));

// Calcular el precio inicial
$mercancia = $precioUnitario * $unidades;

if ($codigo === '') {
    $descuento = 'No se ha introducido ningún código de descuento.';
} elseif (!array_key_exists($codigo, $codigos)) {
    $descuento = 'El código introducido no es válido.';
} else {
    $codigoDescuento = $codigos[$codigo];
    $descuento = $mercancia * ($codigoDescuento / 100);
    $mercancia = $mercancia - $descuento;
}

// Sumo el transporte a la mercancía
$precioBase = $mercancia + $transportes[$transporte];

// Calculo IVA: Precio sin IVA × 0,21
$iva = $precioBase * 0.21;

// Precio total
$precioTotal = $precioBase + $iva;

//Mensaje si precio total supera los 500€
$mensaje = '';

if ($precioTotal > 500) {
    $mensaje = '(El precio total supera los 500€)';
}

// Cargamos la vista
require __DIR__ . '/vistas/ticket.php';

