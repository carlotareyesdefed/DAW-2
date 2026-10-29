<?php
declare(strict_types=1);
class Libro
{
    //propiedades privadas, constructor, métodos prestar, devolver y descripción.
    private $isbn;
    private $titulo;
    private $autor;
    private $disponible;

    public function __construct($isbn, $titulo, $autor)
    {
        $this->isbn = $isbn;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->disponible = true;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function prestar(): bool
    {
        if ($this->disponible) {
            $this->disponible = false;
            return true;
        }

        return false;
    }

    public function devolver(): bool
    {
        if (!$this->disponible) {
            $this->disponible = true;
            return true;
        }

        return false;
    }

    public function estaDisponible(): bool
    {
        return $this->disponible;
    }

    public function descripcion(): string
    {
        $estado = $this->estaDisponible() ? "Disponible" : "No disponible";

        return "Título: " . $this->titulo .
            " || Autor: " . $this->autor .
            " || ISBN: " . $this->isbn .
            " || Estado de préstamo: " . $estado;
    }
}
