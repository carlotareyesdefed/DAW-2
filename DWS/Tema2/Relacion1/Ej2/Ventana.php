<?php
class Ventana
{
    private bool $abierta = false;

    function abrir(): void
    {
        $this->abierta = true;
    }

    function cerrar(): void
    {
        $this->abierta = false;
    }

    function estaAbierta(): bool
    {
        return $this->abierta;
    }

    function __tostring()
    {
        return $this->estaAbierta() ? "La ventana está abierta" : "La ventana está cerrada";
    }
}
