<?php
declare(strict_types=1);

require_once __DIR__ . "/EnvioNormal.php";
require_once __DIR__ . "/EnvioExpress.php";

$envio1 = new EnvioNormal(5);
$envio2 = new EnvioExpress(3, "EXP123");

echo "Precio envío normal: " . $envio1->calcularPrecio() . " €<br>";

echo "Precio envío express: " . $envio2->calcularPrecio() . " €<br>";

if ($envio2 instanceof Rastreable) {
    echo "Código de seguimiento: " . htmlspecialchars(
        $envio2->codigoSeguimiento(),
        ENT_QUOTES,
        'UTF-8'
    ) . " €<br>";
}

try {
    $envio3 = new EnvioNormal(0);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
}