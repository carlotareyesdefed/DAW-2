<?php
require_once __DIR__ . '/repositorioPreguntas.php';

// EJERCICIO 07 - PARTE A: GENERACIÓN.

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}

$asignatura = (string) ($_POST['asignatura'] ?? '');
$numeroDePreguntas = filter_var(
    $_POST['numeroDePreguntas'] ?? null,
    FILTER_VALIDATE_INT
);

if (
    $asignatura == '' ||
    $numeroDePreguntas === false ||
    $numeroDePreguntas === null ||
    $numeroDePreguntas < 1 ||
    $numeroDePreguntas > 5
) {
    exit("Introduce una asignatura y un número de preguntas correcto.");
}

if (!array_key_exists($asignatura, $repositorioPreguntas)) {
    exit("La asignatura seleccionada no existe.");
}

$preguntasAsignatura = $repositorioPreguntas[$asignatura];

$preguntas = (array) array_rand($preguntasAsignatura, $numeroDePreguntas);

echo "<form action='comprobarTest.php' method='POST'>";

foreach ($preguntas as $pregunta) {

    echo "<p>" . htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') . "</p>";

    $opciones = $preguntasAsignatura[$pregunta];

    foreach ($opciones as $indice => $opcion) {

        echo "<label>";

        echo "<input type='radio'
            name='respuestas[" . htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') . "]'
            value='" . htmlspecialchars((string) $indice, ENT_QUOTES, 'UTF-8') . "'
            required>";

        echo htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8');

        echo "</label><br>";
    }

    echo "<br>";
}

echo "<input type='hidden' name='asignatura' value='"
    . htmlspecialchars($asignatura, ENT_QUOTES, 'UTF-8')
    . "'>";

echo "<button type='submit'>Comprobar respuestas</button>";

echo "</form>";