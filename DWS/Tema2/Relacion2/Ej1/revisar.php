<?php
declare(strict_types=1);
require_once __DIR__ . '/incidencias.php';

session_start();

// Comprobar que el usuario está identificado
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

// Comprobar que se ha enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

// Recuperar el identificador
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

if ($id === false  || !isset($incidencias[$id])) {
    http_response_code(400);
    exit('Identificador no válido');
}

// Inicializar el array de revisadas si es necesario
if (
    !isset($_SESSION['revisadas']) ||
    !is_array($_SESSION['revisadas'])
) {
    $_SESSION['revisadas'] = [];
}

// Guardar el id si todavía no está revisado
if (!in_array($id, $_SESSION['revisadas'], true)) {
    $_SESSION['revisadas'][] = $id;
}

// Volver al panel
header('Location: panel.php');
exit;

