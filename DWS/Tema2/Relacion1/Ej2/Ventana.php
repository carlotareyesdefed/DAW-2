<?php
class Ventana
{
    private bool $abierta = false;

    public function abrir(): void
    {
        $this->abierta = true;
    }

    public function cerrar(): void
    {
        $this->abierta = false;
    }

    public function estaAbierta(): bool
    {
        return $this->abierta;
    }

    public function __tostring(): string
    {
        return $this->estaAbierta() ? "abierta" : "cerrada";
    }
}
