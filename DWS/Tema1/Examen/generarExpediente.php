<?php
require_once __DIR__ . '/datosRobo.php';
require_once __DIR__ . '/formulario.php';

//Recibir los datos del formulario inicial. Validarlos y obtener una selección aleatoria de pistas sin repetir.
exigirMetodo('POST');

$nombre = trim((string) ($_POST['nombre'] ?? ''));
$barrio = $_POST['barrio'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$antiguedad = validarEntero('antiguedad', 0, 5);
$cantidadPistas = validarEntero('cantidad', 3, 5);

if ($nombre === '' || validarClaveCatalogo($barrio, $barrios) === false || validarClaveCatalogo($modelo, $modelos) === false || $antiguedad === false || $cantidadPistas === false) {
    exit('Alguno de los datos introducidos es incorrecto');
}

$seleccionadas = elegirClavesAleatorias($pistas, $cantidadPistas);

// Crear el formulario POST hacia analizarExpediente.php.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Operación Cortacandados - Generar expediente</title>
</head>

<body>
    <h1>Expediente de robo</h1>
    <!-- TODO: Mostrar los datos del expediente. -->
    <h2> Expediente generado </h2>
    <p><b>Denunciante: </b> <?= escapar($nombre) ?></p>
    <p><b>Barrio: </b> <?= escapar($barrio) ?></p>
    <p><b>Modelo de la bicicleta: </b> <?= escapar($modelo) ?></p>
    <p><b>Antigüedad: </b> <?= $antiguedad ?> años.</p>

    <!-- Mostrar las pistas aleatorias, sus radios y los campos ocultos. -->
    <h3>Pistas:</h3>
    <form action="analizarExpediente.php" method="post">
        <input type="hidden" name="nombre" value="<?= escapar($nombre) ?>">
        <input type="hidden" name="barrio" value="<?= escapar($barrio) ?>">
        <input type="hidden" name="modelo" value="<?= escapar($modelo) ?>">
        <input type="hidden" name="antiguedad" value="<?= $antiguedad ?>">
        <input type="hidden" name="cantidad" value="<?= $cantidadPistas ?>">

        <?php foreach ($seleccionadas as $pista): ?>
            <input type="hidden" name="pistas[]" value="<?= escapar($pista) ?>">
            <p><b><?= $pista ?>: </b> <?= $pistas[$pista]['descripcion'] ?></p>
            <input type="radio" id="investigar_<?= escapar($pista) ?>" name="decisiones[<?= ($pista) ?>]"
                value="Investigar">

            <label for="investigar_<?= escapar($pista) ?>">Investigar</label>

            <input type="radio" id="descartar_<?= escapar($pista) ?>" name="decisiones[<?= ($pista) ?>]" value="Descartar">

            <label for="descartar_<?= escapar($pista) ?>">Descartar</label>
            <br><br>
        <?php endforeach; ?>

        <button type="submit">Generar expediente</button>
    </form>


</body>

</html>