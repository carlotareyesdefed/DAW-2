<?php
require_once __DIR__ . '/repositorioPreguntas.php';
function mostrar($dato): string
{
    return htmlspecialchars((string) $dato, ENT_QUOTES, 'UTF-8');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario para crear un test.');
}
$asignatura = $_POST['asignatura'] ?? null;
$cantidad = filter_var(
    $_POST['numeroDePreguntas'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1, 'max_range' => 5]]
);
if (
    !is_string($asignatura) || !array_key_exists($asignatura, $repositorioPreguntas) ||
    $cantidad === false
) {
    exit('Elige una asignatura y un número de preguntas entre 1 y 5.');
}
$seleccionadas = (array) array_rand($repositorioPreguntas[$asignatura], $cantidad);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Test de práctica</title>
</head>

<body>
    <h1>Test de <?php echo mostrar(ucfirst($asignatura)); ?></h1>
    <form action="comprobarTest.php" method="post">
        <input type="hidden" name="asignatura" value="<?php echo mostrar($asignatura); ?>">
        <input type="hidden" name="cantidad" value="<?php echo $cantidad; ?>">
        <?php foreach ($seleccionadas as $indice => $pregunta): ?>
            <fieldset>
                <legend><?php echo mostrar($pregunta); ?></legend>
                <input type="hidden" name="preguntas[<?php echo $indice; ?>]" value="<?php echo mostrar($pregunta); ?>">
                <?php foreach ($repositorioPreguntas[$asignatura][$pregunta] as $respuesta): ?>
                    <label><input type="radio" name="respuestas[<?php echo $indice; ?>]"
                            value="<?php echo mostrar($respuesta); ?>"> <?php echo mostrar($respuesta); ?></label><br>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
        <button type="submit">Corregir test</button>
    </form>
</body>

</html>