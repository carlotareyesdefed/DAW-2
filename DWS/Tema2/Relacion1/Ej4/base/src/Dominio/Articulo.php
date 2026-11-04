<?php
namespace App\Dominio;

use InvalidArgumentException;
use App\Excepciones\StockInsuficienteException;

class Articulo
{
    private string $codigo;
    private string $nombre;
    private int $stock;

    public function __construct(string $codigo, string $nombre, int $stockInicial)
    {
        if (trim($codigo) === '' || trim($nombre) === '') {
            throw new InvalidArgumentException(
                "El código y el nombre no pueden estar vacíos"
            );
        }
        $this->codigo = $codigo;
        $this->nombre = $nombre;

        if ($stockInicial < 0) {
            throw new InvalidArgumentException(
                "El stock no puede ser negativo"
            );
        }
        $this->stock = $stockInicial;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
    function aumentarStock(int $cantidad): int
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException(
                "La cantidad no puede ser negativa ni igual a 0"
            );
        }

        return $this->stock += $cantidad;
    }

    function retirarStock(int $cantidad): int
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException(
                "La cantidad no puede ser negativa ni igual a 0"
            );
        }

        if ($cantidad > $this->stock) {
            throw new StockInsuficienteException(
                "Cantidad solicitada: " . $cantidad . " || Stock disponible: " . $this->stock
            );
        }

        return $this->stock -= $cantidad;
    }

}