<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de agenda</title>
</head>

<body>
    <h1>Actos encontrados</h1>

    <h2>Filtros seleccionados</h2>

    <p>Provincia: <?= htmlspecialchars($provincia) ?></p>
    <p>Partido: <?= htmlspecialchars($partido) ?></p>
    <p>Tipo: <?= htmlspecialchars($tipo) ?></p>
    <p>Límite: <?= $limite ?></p>

    <h2>Resumen de resultados</h2>

    <p>Total de coincidencias: <?= $totalCoincidencias ?></p>
    <p>Aforo acumulado: <?= $aforoTotal ?></p>

    <?php if ($totalCoincidencias === 0): ?>

        <p>No se han encontrado actos que coincidan con los filtros seleccionados.</p>

    <?php else: ?>

        <table border="1">

            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Provincia</th>
                    <th>Partido</th>
                    <th>Tipo</th>
                    <th>Lugar</th>
                    <th>Aforo</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($actosVisibles as $acto): ?>

                    <tr>
                        <td><?= htmlspecialchars($acto["fecha"]) ?></td>
                        <td><?= htmlspecialchars($acto["hora"]) ?></td>
                        <td><?= htmlspecialchars($acto["provincia"]) ?></td>
                        <td><?= htmlspecialchars($acto["partido"]) ?></td>
                        <td><?= htmlspecialchars($acto["tipo"]) ?></td>
                        <td><?= htmlspecialchars($acto["lugar"]) ?></td>
                        <td><?= (int) $acto["aforo"] ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

    <p><a href="agenda.html">Nueva búsqueda</a></p>
</body>

</html>