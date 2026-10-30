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

        $this->libros[$libro->getIsbn()] = $libro;
    }

    public function buscarLibro($isbn): ?Libro
    {

        /*  foreach ($this->libros as $libro) {
            if ($libro->getIsbn() === $isbn) {
                return $libro;
            }
        }


        return null;
        */

        return $this->libros[$isbn] ?? null;
    }

    public function disponibles(): array
    {
        /* $disponibles = [];
         foreach ($this->libros as $libro) {
             if ($libro->estaDisponible()) {
                 $disponibles[$libro->getIsbn()] = $libro;
             }
         }
         return $disponibles;*/

        return array_values(
            array_filter(
                $this->libros,
                fn(Libro $li) => $li->estaDisponible()
            )
        );
    }




}
