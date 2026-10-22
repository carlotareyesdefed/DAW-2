<?php
$ciudades = [
    'Granada',
    'Málaga',
    'Sevilla',
    'Córdoba'
];

$turnos = [
    'Mañana',
    'Tarde',
    'Noche'
];

$aficiones = [
    'Deporte',
    'Música',
    'Lectura',
    'Videojuegos'
];

$edadMinima = 18;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario dinámico</title>
</head>

<body>
    <h1>Formulario de inscripción</h1>

    <form action="procesar.php" method="POST">

        <!-- TEXT -->
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <br><br>

        <!-- NUMBER -->
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" min="<?= $edadMinima ?>" max="100" required>

        <br><br>

        <!-- SELECT CON FOREACH -->
        <label for="ciudad">Ciudad:</label>

        <select name="ciudad" id="ciudad" required>
            <option value="" disabled selected>
                Selecciona una ciudad
            </option>

            <?php foreach ($ciudades as $ciudad): ?>
            <option value="<?= htmlspecialchars($ciudad) ?>">
                <?= htmlspecialchars($ciudad) ?>
            </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <!-- RADIO CON FOREACH -->
        <p>Turno preferido:</p>

        <?php foreach ($turnos as $turno): ?>
        <label>
            <input type="radio" name="turno" value="<?= htmlspecialchars($turno) ?>" required>
            <?= htmlspecialchars($turno) ?>
        </label>
        <?php endforeach; ?>

        <br><br>

        <!-- CHECKBOX CON FOREACH -->
        <p>Aficiones:</p>

        <?php foreach ($aficiones as $aficion): ?>
        <label>
            <input type="checkbox" name="aficiones[]" value="<?= htmlspecialchars($aficion) ?>">
            <?= htmlspecialchars($aficion) ?>
        </label>
        <?php endforeach; ?>

        <br><br>

        <!-- IF PARA MOSTRAR UN CAMPO OPCIONAL -->
        <?php if ($edadMinima >= 18): ?>
        <label for="observaciones">
            Observaciones:
        </label>
        <input type="text" id="observaciones" name="observaciones">
        <?php endif; ?>

        <br><br>

        <button type="submit">Enviar</button>

    </form>
</body>

</html>