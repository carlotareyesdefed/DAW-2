<?php
require_once __DIR__ . "/Coche.php";
declare(strict_types=1);
require_once __DIR__ . '/Vehiculo.php';

$coche = new Coche('Gasolina', 'C', 4);
$coche->getPuerta(0)->abrir();
$coche->getPuerta(0)->getVentana()->abrir();
echo htmlspecialchars((string) $coche, ENT_QUOTES, 'UTF-8') . '<br>';
$coche->cerrarVehiculo();
echo htmlspecialchars((string) $coche, ENT_QUOTES, 'UTF-8') . '<br>';
// Ejemplo ficticio de etiquetas permitidas: la normativa real depende de ciudad, fecha y zona.
echo $coche->puedeEntrarZBE(['0', 'ECO', 'C']) ? 'Permitido en el ejemplo' : 'No permitido en el ejemplo';