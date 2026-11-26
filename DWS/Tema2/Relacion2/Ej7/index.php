<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/EquipoRepositorio.php';
function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
$error = '';
$equipos = [];

try {
    $repo = new EquipoRepositorio(conectar());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = (string) ($_POST['accion'] ?? '');

        if ($accion === 'crear') {
            $repo->crear((string) ($_POST['nombre'] ?? ''), ($_POST['unidades'] ?? null));
        }
        header('Location: index.php');
        exit;
    }
    $equipos = $repo->listar();
} catch (InvalidArgumentException $errorValidacion) {
    http_response_code(400);
    $error = $errorValidacion->getMessage();
} catch (PDOException $errorBD) {
    http_response_code(500);
    $error = 'No se pudo completar la operación. Revisa la conexión o los datos duplicados.';
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>CRUD de equipos</title>
</head>

<body>
    <?php if ($error !== ''): ?>
        <p><?= h($error) ?></p>
    <?php endif; ?>
    <h1>Equipos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Unidades</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><?= $equipo['id'] ?></td>
                    <td><?= h($equipo['nombre']) ?></td>
                    <td><?= $equipo['unidades'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <h2>Añadir equipo</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="accion" value="crear">
        <input name="nombre" placeholder="Nombre" minlength="2" maxlength="80" required>
        <input type="number" name="unidades" min="0" required>
        <button type="submit">Añadir</button>
    </form>
</body>