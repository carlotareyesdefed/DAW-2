<?php
declare(strict_types=1);
require_once __DIR__ . '/sesion.php';

// Solo aceptar peticiones POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Comprobar que la sesión es válida.
comprobarSesion();

// Recuperar el tipo de movimiento.
$tipo = $_POST['tipo'] ?? '';

// Comprobar que el tipo es válido.
if ($tipo !== 'entrada' && $tipo !== 'salida') {
    header('Location: panel.php');
    exit;
}

// Recuperar los movimientos.
$movimientos = $_SESSION['movimientos'];

// Comprobar si es el primer movimiento.
if (empty($movimientos)) {
    if ($tipo !== 'entrada') {
        header('Location: panel.php');
        exit;
    }
} else {
    // Recuperar el último movimiento.
    $ultimo = end($movimientos);

    // No permitir dos movimientos iguales seguidos.
    if ($ultimo['tipo'] === $tipo) {
        header('Location: panel.php');
        exit;
    }
}

// Guardar el movimiento válido.
$_SESSION['movimientos'][] = [
    'tipo' => $tipo,
    'fecha' => date('d/m/Y H:i:s')
];

// Redirigir al panel.
header('Location: panel.php');
exit;
