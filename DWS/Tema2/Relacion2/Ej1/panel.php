<?php
declare(strict_types=1);
require_once __DIR__ . '/incidencias.php';
// iniciar sesión
session_start();

// si no existe redirigir a login.php
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$nombre = escapar($_SESSION['nombre']);
$perfil = escapar($_SESSION['perfil']);


// Recuperar incidencias revisadas.
$revisadas = $_SESSION['revisadas'] ?? [];

if (!is_array($revisadas)) {
    $revisadas = [];
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Panel</title>
</head>

<body>
    <h1>Incidencias del centro</h1>
    <h2>Hola, <?= $nombre ?></h2>
    <p><strong>Perfil: </strong> <?= $perfil ?></p>

    <table border="1">
        <thead>
            <tr>
                <th>Aula</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($incidencias as $id => $i): ?>

                <?php
                $estaRevisada = in_array($id, $revisadas, true);
                ?>

                <tr>
                    <td><?= escapar($i['aula']) ?></td>
                    <td><?= escapar($i['descripcion']) ?></td>

                    <td>
                        <?= $estaRevisada ? 'Revisada' : 'Pendiente' ?>
                    </td>

                    <td>
                        <?php if (!$estaRevisada): ?>
                            <form action="revisar.php" method="post">
                                <input type="hidden" name="id" value="<?= escapar($id) ?>">
                                <button type="submit">
                                    Marcar como revisada
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>


    <!-- TODO: saludo, estado de incidencias y botones POST para revisar. -->
    <!-- TODO: enlace al informe y formulario POST de cierre de sesión. -->
</body>

</html>