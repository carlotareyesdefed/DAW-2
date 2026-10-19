<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe del pacto</title>
</head>

<body>

    <h1>Informe del pacto (simulación)</h1>

    <h2>Datos de la Asamblea</h2>

    <table border="1">
        <tbody>
            <tr>
                <th>Asamblea</th>
                <td><?= escapar($escenarios[$escenario]['nombre']) ?></td>
            </tr>

            <tr>
                <th>Total de escaños</th>
                <td><?= $totalEscanos ?></td>
            </tr>

            <tr>
                <th>Mayoría absoluta</th>
                <td><?= $mayoria ?></td>
            </tr>

            <tr>
                <th>Socios del pacto</th>
                <td>
                    <ul>
                        <?php foreach ($socios as $socio): ?>
                            <li><?= escapar($socio) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>

    <h2>Resultado del pacto</h2>

    <table border="1">
        <tbody>
            <tr>
                <th>Escaños del pacto</th>
                <td><?= $escanosPacto ?></td>
            </tr>

            <tr>
                <th>Mayoría absoluta</th>
                <td><?= $mayoria ?></td>
            </tr>

            <tr>
                <th>Escaños que faltan</th>
                <td><?= $escanosParaMayoria ?></td>
            </tr>

            <tr>
                <th>Escaños sobrantes</th>
                <td><?= $escanosSobrantes ?></td>
            </tr>
        </tbody>
    </table>

    <p><?= escapar($mensajeMayoria) ?></p>

    <h2>Alternativas de coalición</h2>

    <p>
        Se muestra qué ocurriría si se incorporase
        cada partido externo de forma individual.
    </p>

    <table border="1">

        <thead>
            <tr>
                <th>Partido externo</th>
                <th>Escaños que aporta</th>
                <th>Total de la nueva coalición</th>
                <th>¿Alcanza mayoría absoluta?</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($alternativas as $partido => $datos): ?>
                <tr>
                    <td><?= escapar($partido) ?></td>

                    <td><?= $datos['escanos'] ?></td>

                    <td><?= $datos['total'] ?></td>

                    <td>
                        <?php if ($datos['mayoria']): ?>
                            Sí
                        <?php else: ?>
                            No
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

    <h2>Socio adicional recomendado</h2>

    <p><?= escapar($mensajePacto) ?></p>

    <p><a href="pactos.html">Analizar otro pacto</a></p>

</body>

</html>