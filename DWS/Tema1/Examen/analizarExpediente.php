<?php
require_once __DIR__ . '/datosRobo.php';
require_once __DIR__ . '/formulario.php';

// Recuperar los datos y las claves de las pistas mostradas.
exigirMetodo('POST');

$nombre = trim((string) ($_POST['nombre'] ?? ''));
$barrio = $_POST['barrio'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$antiguedad = validarEntero('antiguedad', 0, 5);
$cantidadPistas = validarEntero('cantidad', 3, 5);
$decisiones = $_POST['decisiones'] ?? [];

// Analizar cada decisión y compararla con el catálogo.
$coincidente = 0;

foreach ($decisiones as $pista => $decision) {
    $fiabilidad = $pistas[$pista]['fiable'];

    $decisionCorrecta = $fiabilidad ? 'Investigar' : '';

    if ($decision === $decisionCorrecta) {
        $coincidente++;
    }
}

// Calcular el valor de investigación y la prioridad.
$prioridad = calcularPrioridad($coincidente);

// Calcular el precio actualizado de la bicicleta.
$precioOriginal = $modelos[$modelo]['precio'];
$descuento = $precioOriginal * $antiguedad * 0.10;
$precioActual = $precioOriginal - $descuento;

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Operación Cortacandados - Informe final</title>
</head>

<body>
    <h1>Informe de investigación</h1>
    <!-- TODO: Mostrar la valoración, tabla de pistas y resumen del análisis. -->
    <h2> Informe generado </h2>
    <p><b>Denunciante: </b> <?= escapar($nombre) ?></p>
    <p><b>Barrio: </b> <?= escapar($barrio) ?></p>
    <p><b>Modelo de la bicicleta: </b> <?= escapar($modelo) ?></p>
    <p><b>Precio Original: </b> <?= euros($precioOriginal) ?> </p>
    <p><b>Antigüedad: </b> <?= $antiguedad ?> años.</p>
    <p><b>Porcentaje de depreciación: </b> <?= $antiguedad * 0.10 ?> %</p>
    <p><b>Descuento: </b> <?= euros($descuento) ?></p>
    <p><b>Valor actual: </b> <?= euros($precioActual) ?> </p>

    <table>
        <caption>Actos encontrados</caption>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Provincia</th>
                <th>Lugar</th>
                <th>Aforo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actosVisibles as $acto): ?>
                <tr>
                    <td><?= htmlspecialchars($acto['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($acto['provincia'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($acto['lugar'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $acto['aforo'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



    <p><a href="denuncia.html">Nuevo expediente</a></p>
</body>

</html>