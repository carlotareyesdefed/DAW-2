<?php
require_once __DIR__ . "/Envio.php";
class EnvioNormal extends Envio
{

    public function calcularPrecio(): float
    {
        return 4 + 1.5 * $this->pesoKg;
    }

}