<?php

use App\Dominio\Articulo;
use App\Excepciones\StockInsuficienteException;

require_once __DIR__ . '/src/Excepciones/StockInsuficienteException.php';
require_once __DIR__ . '/src/Dominio/Articulo.php';
// TODO: importar con use y ejecutar pruebas.

$articulo = new Articulo("A-101", "Cuaderno", 10);
echo $articulo->getCodigo() . "<br>";
echo $articulo->getNombre() . "<br>";
echo $articulo->getStock() . "<br>";

echo $articulo->aumentarStock(5) . "<br>";
echo $articulo->retirarStock(8) . "<br>";

try {
    $articulo->retirarStock(10);

} catch (StockInsuficienteException $e) {
    echo $e->getMessage() . "<br>";
}

try {
    $articulo->aumentarStock(0);

} catch (InvalidArgumentException $e) {
    echo $e->getMessage() . "<br>";
}

echo $articulo->retirarStock(7) . "<br>";