<?php
declare(strict_types=1);

require_once __DIR__ . '/sesion.php';
comprobarSesion();

$nombre = $_SESSION['nombre'];
$movimientos = $_SESSION['movimientos'];
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Panel de turnos</title>
</head>

<body>
    <h1>Bienvenido, <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?></h1>

    <h2>Registrar movimientos</h2>

    <form action="registrar.php" method="post">
        <input type="hidden" name="tipo" value="entrada">
        <button type="submit">Registrar entrada</button>
    </form>
    <br>
    <form action="registrar.php" method="post">
        <input type="hidden" name="tipo" value="salida">
        <button type="submit">Registrar salida</button>
    </form>

    <h2>Historial de movimientos</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Fecha y hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $movimiento): ?>
                <tr>
                    <td><?= htmlspecialchars($movimiento['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($movimiento['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <form action="salir.php" method="post">
        <button type="submit">Cerrar sesión</button>
    </form>
</body>

</html>