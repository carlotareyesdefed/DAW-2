<?php
declare(strict_types=1);

function comprobarSesion(): void
{
    // Iniciar o recuperar la sesión.
    session_start();

    // Comprobar si el usuario está identificado.
    if (!isset($_SESSION['nombre'])) {
        header('Location: acceso.php');
        exit;
    }

    // Comprobar si han pasado más de 180 segundos.
    if (time() - $_SESSION['ultimaActividad'] > 180) {
        // Vaciar los datos de la sesión.
        $_SESSION = [];

        // Eliminar la cookie de sesión.
        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $parametros['path'],
                'domain' => $parametros['domain'],
                'secure' => $parametros['secure'],
                'httponly' => $parametros['httponly'],
                'samesite' => $parametros['samesite'] ?? 'Lax'
            ]);
        }

        // Destruir la sesión.
        session_destroy();

        header('Location: acceso.php?motivo=expirada');
        exit;
    }

    // Actualizar la última actividad.
    $_SESSION['ultimaActividad'] = time();
}
