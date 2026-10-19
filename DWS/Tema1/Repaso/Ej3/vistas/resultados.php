<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultado del escrutinio</title>
</head>

<body>

    <h1>Resultado ficticio del escrutinio</h1>

    <h2>Resultados por partido</h2>

    <table border="1">

        <thead>
            <tr>
                <th>Partido</th>
                <th>Votos</th>
                <th>Porcentaje sobre votos válidos</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($porcentajeVotos as $partido => $porcentaje): ?>
                <tr>
                    <td><?= htmlspecialchars($partido, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $votosPartido[$partido] ?></td>
                    <td><?= number_format($porcentaje, 2, ',', '.') ?> %</td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

    <h2>Resumen del escrutinio</h2>

    <table border="1">

        <tbody>
            <tr>
                <th>Censo electoral</th>
                <td><?= $censo ?></td>
            </tr>

            <tr>
                <th>Votos emitidos</th>
                <td><?= $votosEmitidos ?></td>
            </tr>

            <tr>
                <th>Votos válidos</th>
                <td><?= $votosValidos ?></td>
            </tr>

            <tr>
                <th>Votos a candidaturas</th>
                <td><?= $candidaturas ?></td>
            </tr>

            <tr>
                <th>Votos en blanco</th>
                <td><?= $votosBlanco ?></td>
            </tr>

            <tr>
                <th>Votos nulos</th>
                <td><?= $votosNulos ?></td>
            </tr>

            <tr>
                <th>Abstención</th>
                <td><?= $abstencion ?></td>
            </tr>

            <tr>
                <th>Participación</th>
                <td><?= number_format($participacion, 2, ',', '.') ?> %</td>
            </tr>
        </tbody>

    </table>

    <h2>Resultado final</h2>

    <?php if (count($partidosEmpatados) > 1): ?>

        <p>
            Hay un empate en el primer puesto entre:
            <?= htmlspecialchars(implode(', ', $partidosEmpatados), ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php else: ?>

        <p>
            El partido ganador es:
            <strong><?= htmlspecialchars($partidosEmpatados[0], ENT_QUOTES, 'UTF-8') ?></strong>
        </p>

    <?php endif; ?>

    <p>
        Número de partidos que alcanzan el umbral didáctico del 5 %:
        <strong><?= $umbral5 ?></strong>
    </p>

    <p>
        El umbral del 5 % es únicamente didáctico.
        No representa un reparto real de escaños.
    </p>

    <p><a href="escrutinio.html">Nuevo recuento</a></p>

</body>

</html>