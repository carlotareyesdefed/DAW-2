<?php
declare(strict_types=1);
require_once __DIR__ . '/usuarios.php';
// iniciar o recuperar la sesión.
session_start();

// si el usuario ya está identificado, redirigir a panel.php.
if (isset($_SESSION['nombre'])) {
    header('Location: panel.php');
    exit;
}
// validar las credenciales recibidas por POST.
function autenticar(string $email, string $clave): ?array
{
    $cuentas = obtenerUsuarios();
    $email = strtolower(trim($email));
    if (!isset($cuentas[$email])) {
        return null;
    }
    if (!password_verify($clave, $cuentas[$email]['hash'])) {
        return null;
    }
    return $cuentas[$email];
}

// regenerar el ID, guardar los datos necesarios y redirigir.
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $clave = (string) ($_POST['clave'] ?? '');
    $datos = autenticar($email, $clave);
    if ($datos !== null) {
        session_regenerate_id(true);
        $_SESSION['nombre'] = $datos['nombre'];
        $_SESSION['correo'] = $email;
        $_SESSION['perfil'] = $datos['perfil'];
        header('Location: panel.php');
        exit;
    }
    $error = 'Datos de acceso incorrectos.';
}

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Acceso a incidencias</title>
</head>

<body>
    <h1>Acceso al panel de incidencias</h1>
    <!-- TODO: mostrar errores y el formulario con correo y contraseña. -->
    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
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