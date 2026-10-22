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
$clavesPistas = $_POST['pistas'] ?? [];

if ($nombre === '' || validarClaveCatalogo($barrio, $barrios) === false || validarClaveCatalogo($modelo, $modelos) === false || $antiguedad === false || $cantidadPistas === false) {
    exit('Alguno de los datos introducidos es incorrecto');
}

// 1. Comprobar que las pistas son un array
if (!is_array($clavesPistas)) {
    exit('Las pistas no son válidas');
}

// 2. Comprobar que las claves existen en el catálogo
foreach ($clavesPistas as $clave) {
    if (
        !is_string($clave) ||
        !array_key_exists($clave, $pistas)
    ) {
        exit('Pista incorrecta');
    }
}

// 3. Comprobar cantidad y duplicados
if (
    count($clavesPistas) !== $cantidadPistas ||
    count($clavesPistas) !== count(array_unique($clavesPistas))
) {
    exit('El conjunto de pistas no es válido');
}

// 4. Comprobar que las decisiones son un array
if (!is_array($decisiones)) {
    exit('Decisiones incorrectas');
}

// 5. Validar las decisiones
foreach ($decisiones as $pista => $decision) {
    if (
        !in_array($pista, $clavesPistas, true) ||
        !in_array($decision, ['Investigar', 'Descartar'], true)
    ) {
        exit('Decisión incorrecta');
    }
}

// Analizar cada decisión y compararla con el catálogo.
$coincidente = [];

foreach ($clavesPistas as $pista) {
    $decision = $decisiones[$pista] ?? '';
    $fiabilidad = $pistas[$pista]['fiable'];

    $decisionCorrecta = $fiabilidad ? 'Investigar' : 'Descartar';

    if ($decision === '') {
        $coincidente[$pista] = 'Sin decidir';
    } elseif ($decision === $decisionCorrecta) {
        $coincidente[$pista] = 'Coincide';
    } else {
        $coincidente[$pista] = 'No coincide';
    }
}

$totalCoincidencias = 0;

foreach ($coincidente as $resultado) {
    if ($resultado === 'Coincide') {
        $totalCoincidencias++;
    }
}

// Otra opcion: array_count_values($coincidente)['Coincide'] ?? 0

// Calcular el valor de investigación y la prioridad.
$valorTotal = 0;

foreach ($clavesPistas as $pista) {

    $decision = $decisiones[$pista] ?? '';

    if (
        $pistas[$pista]['fiable'] &&
        $decision === 'Investigar'
    ) {
        $valorTotal += $pistas[$pista]['valor'];
    }
}

$prioridad = calcularPrioridad($valorTotal);

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
    <p><b>Porcentaje de depreciación: </b> <?= $antiguedad * 10 ?> %</p>
    <p><b>Descuento: </b> <?= euros($descuento) ?></p>
    <p><b>Valor actual: </b> <?= euros($precioActual) ?> </p>

    <table border="1">
        <thead>
            <tr>
                <th>Pistas</th>
                <th>Decisión</th>
                <th>Recomendación</th>
                <th>Resultado comparación</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clavesPistas as $pista): ?>
                <?php $decision = $decisiones[$pista] ?? ''; ?>
                <tr>
                    <td><?= escapar($pista) ?></td>
                    <td><?= escapar($decision === '' ? 'Sin decidir' : $decision) ?></td>
                    <td><?= $pistas[$pista]['fiable'] ? 'Investigar' : 'Descartar' ?></td>
                    <td><?= escapar($coincidente[$pista]) ?></td>
                    <td><?= $pistas[$pista]['valor'] ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2"><b>Coincidencias: </b> <?= count($coincidente) ?></td>
                <td colspan="2"><b>Valor total: </b> <?= $valorTotal ?></td>
                <td colspan="2"><b>Prioridad: </b> <?= $prioridad ?></td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <p><a href="denuncia.html">Nuevo expediente</a></p>
</body>

</html>