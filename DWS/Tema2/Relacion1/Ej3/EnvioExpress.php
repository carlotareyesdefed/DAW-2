<?php
require_once __DIR__ . "/Envio.php";
require_once __DIR__ . "/Rastreable.php";

class EnvioExpress extends Envio implements Rastreable
{
    private string $codigo;

    public function __construct(float $pesoKg, string $codigo)
    {
        parent::__construct($pesoKg);
        $this->codigo = $codigo;
    }
    public function calcularPrecio(): float
    {
        return 9 + 2.75 * $this->pesoKg;
    }

    public function codigoSeguimiento(): string
    {
        return $this->codigo;

    }
}