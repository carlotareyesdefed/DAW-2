<?php
declare(strict_types=1);
require_once __DIR__ . '/preguntas.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
// TODO: validar sesión y opción, registrar un solo acierto y avanzar.
session_start();

if (!isset($_SESSION['cuestionario'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SESSION['cuestionario']['pregunta'] < count($preguntas)) {

    $opcion = (string) ($_POST['alias'] ?? '');

    if (in_array($opcion, $preguntas, true)) {

    if($opciones[&$opcion]){

    } else {
        $error = 'No se ha mandando ninguna opción o no existe entre las posibles respuestas.';
    }

} else {
    header('Location: index.php');
    exit;
}



// TODO: redirigir a index.php sin generar HTML.
