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
$prestamos = [];
try {
    $repo = new EquipoRepositorio(conectar());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = (string) ($_POST['accion'] ?? '');
        $id = filter_var(
            $_POST['equipo'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($accion === 'prestar') {
            $repo->prestar($id, (string) ($_POST['nombre'] ?? ''));
        }
        header('Location: index.php');
        exit;
    }
    $equipos = $repo->listarEquipos();
    $prestamos = $repo->listarPrestamos();
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
    <title>Préstamos de equipos</title>
</head>

<body>
    <h2>Equipos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Unidades</th>
                <th>Disponibles</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><?= h($equipo['nombre']) ?></td>
                    <td><?= $equipo['unidades'] ?></td>
                    <td><?= $equipo['disponibles'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <h2>Prestar un equipo</h2>
    <form action="index.php" method="post" style="display:inline">
        <input type="hidden" name="accion" value="prestar">
        <input name="nombre" placeholder="Introduce tu nombre" required>
        <label for="equipo">Equipo:</label>
        <select id="equipo" name="equipo" required>
            <option value="" disabled selected>Elige una opción</option>
            <?php foreach ($equipos as $equipo): ?>
                <option value="<?= $equipo['id'] ?>"><?= h($equipo['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Prestar</button>
    </form>
    <br>
    <h2>Préstamos de equipos</h2>
    <!--   <table border="1">
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Nombre</th>
                <th>Devuelto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamos as $prestamo): ?>
                <tr>
                    <td><?= h($prestamo['nombre']) ?></td>
                    <td><?= $prestamo['unidades'] ?></td>
                    <td><?= $prestamo['disponibles'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table> -->



</body>

</html>