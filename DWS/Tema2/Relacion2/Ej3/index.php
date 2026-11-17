<?php
declare(strict_types=1);
require_once __DIR__ . '/preguntas.php';
// iniciar sesión y tratar POST de inicio de cuestionario.
session_start();

//validar alias, regenerar el ID, guardar los datos necesarios y redirigir.
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['cuestionario'])) {
    $alias = trim((string) ($_POST['alias'] ?? ''));

    if (mb_strlen($alias) >= 2 && mb_strlen($alias) <= 30) {

        session_regenerate_id(true);

        $_SESSION['cuestionario'] = [
            'alias' => $alias,
            'pregunta' => 0,
            'aciertos' => 0
        ];
        header('Location: index.php');
        exit;

    } else {
        $error = 'El alias debe tener entre 2 y 30 caracteres.';
    }
}

// comprobar si hay una partida iniciada
if (isset($_SESSION['cuestionario'])) {
    $indice = $_SESSION['cuestionario']['pregunta'];

    if ($indice < count($preguntas)) {
        $preguntaActual = $preguntas[$indice];
    }
}


// TODO: mostrar inicio, pregunta actual o resultados según el estado.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Cuestionario</title>
</head>

<body>
    <h1>Cuestionario por sesiones</h1>
    <!-- formularios y vista adecuados al estado -->
    <?php if (!isset($_SESSION['cuestionario'])): ?>

        <!-- Formulario del alias -->
        <?php if ($error !== ''): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form action="index.php" method="post">
            <label>Alias
                <input name="alias" type="text" minlength="2" maxlength="30" required>
            </label>
            <button type="submit">Comenzar</button>
        </form>

    <?php elseif ($indice < count($preguntas)): ?>

        <!-- Mostrar pregunta actual -->
        <h2><?= htmlspecialchars($preguntaActual['texto'], ENT_QUOTES, 'UTF-8') ?></h2>

        <form action="responder.php" method="post">
            <?php foreach ($preguntaActual['opciones'] as $opcion): ?>
                <label>
                    <input type="radio" name="opcion" value="<?= htmlspecialchars($opcion) ?>" required>
                    <?= htmlspecialchars($opcion) ?>
                </label>

            <?php endforeach; ?>

        </form>

    <?php else: ?>

        <!-- Mostrar resultado final -->

    <?php endif; ?>
</body>

</html>