<?php
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
    if (
        $nombre !== '' && strlen($nombre) <= 100 &&
        $unidades !== false && $unidades !== null &&
        $unidades >= 0 && $unidades <= 1000000
    ) {
        $sql = 'INSERT INTO equipo (nombre, unidades) VALUES (?, ?)';
        $pdo->prepare($sql)->execute([$nombre, $unidades]);
        header('Location: index.php');
        exit;
    }
    $error = 'Revisa los datos introducidos.';
}
$equipos = $pdo->query('SELECT * FROM equipo ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario del aula</title>
</head>

<body>
    <h1>Catálogo de equipos</h1>
    <p><?= htmlspecialchars($error ?? '') ?></p>
    <form method="post">
        <input name="nombre" placeholder="Nombre" maxlength="100" required>
        <input type="number" name="unidades" min="0" max="1000000" required>
        <button>Añadir</button>
    </form>
    <ul>
        <?php foreach ($equipos as $equipo): ?>
            <li><?= htmlspecialchars($equipo['nombre']) ?>:
                <?= $equipo['unidades'] ?> unidades
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>