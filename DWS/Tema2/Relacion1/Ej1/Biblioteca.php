<?php
declare(strict_types=1);
require_once __DIR__ . '/Libro.php';
class Biblioteca
{
    //array privado y métodos agregar, buscar y disponibles.
    private $libros = [];

    public function agregarLibro(Libro $libro): void
    {
        foreach ($this->libros as $book) {
            if ($book->getIsbn() === $libro->getIsbn()) {
                throw new InvalidArgumentException("El ISBN ya existe");
            }
        }

        $this->libros[] = $libro;
    }

    public function buscarLibro($isbn): ?Libro
    {
        foreach ($this->libros as $libro) {
            if ($libro->getIsbn() === $isbn) {
                return $libro;
            }
        }

        return null;
    }

    public function disponibles(): array
    {
        $disponibles = [];
        foreach ($this->libros as $libro) {
            if ($libro->estaDisponible()) {
                $disponibles[] = $libro;
            }
        }
        return $disponibles;
    }
}
