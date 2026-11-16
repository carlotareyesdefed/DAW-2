<?php
declare(strict_types=1);
require_once __DIR__ . '/cuentas.php';

session_start();

// Si el usuario ya está identificado, redirigir a panel.php.
if (isset($_SESSION['nombre'])) {
    header('Location: panel.php');
    exit;
}

function autenticar(string $email, string $clave): ?array
{
    $cuentas = obtenerCuentas();
    $email = strtolower(trim($email));

    if (!isset($cuentas[$email])) {
        return null;
    }

    if (!password_verify($clave, $cuentas[$email]['hash'])) {
        return null;
    }

    return $cuentas[$email];
}

// Inicializar los datos solo si no existen.
$_SESSION['fallidos'] ??= 0;
$_SESSION['bloqueadoHasta'] ??= 0;

$error = '';

// Mensaje si la sesión ha caducado
$mensaje = '';

if (isset($_GET['motivo']) && $_GET['motivo'] === 'expirada') {
    $mensaje = 'Tu sesión ha caducado por inactividad.';
}

// Comprobar si el bloqueo ha terminado.
if (
    $_SESSION['bloqueadoHasta'] !== 0 &&
    time() >= $_SESSION['bloqueadoHasta']
) {
    $_SESSION['fallidos'] = 0;
    $_SESSION['bloqueadoHasta'] = 0;
}

// Procesar formulario.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $clave = (string) ($_POST['clave'] ?? '');

    // Comprobar primero si el usuario está bloqueado.
    if (time() < $_SESSION['bloqueadoHasta']) {
        $error = 'Acceso bloqueado temporalmente.';

    } elseif ($email !== '' && $clave !== '') {

        $datos = autenticar($email, $clave);

        if ($datos !== null) {
            session_regenerate_id(true);

            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['correo'] = $email;
            $_SESSION['movimientos'] = [];
            $_SESSION['ultimaActividad'] = time();

            // Reiniciar intentos tras acceder correctamente.
            $_SESSION['fallidos'] = 0;
            $_SESSION['bloqueadoHasta'] = 0;

            header('Location: panel.php');
            exit;

        } else {
            // Incrementar los intentos fallidos.
            $_SESSION['fallidos']++;

            if ($_SESSION['fallidos'] >= 3) {
                $_SESSION['bloqueadoHasta'] = time() + 90;
            } else {
                $error = 'Datos de acceso incorrectos.';
            }
        }
    } else {
        $error = 'Debes introducir correo y contraseña.';
    }
}

// Calcular los intentos restantes.
$intentos = 3 - $_SESSION['fallidos'];

// Calcular el tiempo de bloqueo.
$tiempoBloqueo = $_SESSION['bloqueadoHasta'] - time();
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de turnos</title>
</head>

<body>
    <h1>Identificación</h1>

    <?php if ($mensaje !== ''): ?>
        <p><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($tiempoBloqueo > 0): ?>
        <p>
            No puede acceder, inténtelo de nuevo
            en aproximadamente <?= $tiempoBloqueo ?> segundos.
        </p>

    <?php elseif ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>

        <?php if ($_SESSION['fallidos'] > 0): ?>
            <p>
                <strong>Intentos restantes:</strong>
                <?= $intentos ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <form action="acceso.php" method="post">
        <label>Correo
            <input name="email" type="email" required>
        </label>

        <label>Contraseña
            <input name="clave" type="password" required>
        </label>

        <button type="submit">Entrar</button>
    </form>
</body>

</html>