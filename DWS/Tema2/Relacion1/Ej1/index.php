<?php
declare(strict_types=1);

require_once __DIR__ . '/Biblioteca.php';

// Crear tres libros
$libro1 = new Libro("9788420412146", "El Quijote", "Miguel de Cervantes");
$libro2 = new Libro("9788497592208", "Cien años de soledad", "Gabriel García Márquez");
$libro3 = new Libro("9788408172178", "La sombra del viento", "Carlos Ruiz Zafón");

// Crear biblioteca
$biblioteca = new Biblioteca();

// Añadir libros
$biblioteca->agregarLibro($libro1);
$biblioteca->agregarLibro($libro2);
$biblioteca->agregarLibro($libro3);

// Prestar primer libro
var_dump($libro1->prestar()); 

// Intentar prestarlo de nuevo
var_dump($libro1->prestar()); 

// Comprobar estado
var_dump($libro1->estaDisponible()); 

// Devolver el libro
var_dump($libro1->devolver()); 

// Comprobar estado
var_dump($libro1->estaDisponible()); 

// Comprobar ISBN duplicado
try {
    $biblioteca->agregarLibro($libro1);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage() . "<br>";
}

// Mostrar libros disponibles
echo "<h2>Libros disponibles</h2>";

foreach ($biblioteca->disponibles() as $libro) {
    echo $libro->descripcion() . "<br>";
}
