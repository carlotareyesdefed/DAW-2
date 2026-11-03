<?php
declare(strict_types=1);
abstract class Envio
{
    protected float $pesoKg;
    public function __construct(float $pesoKg)
    {
        if ($pesoKg <= 0) {
            throw new InvalidArgumentException(
                "El peso debe ser mayor que cero"
            );
        }
        $this->pesoKg = $pesoKg;
    }

    abstract public function calcularPrecio(): float;
}