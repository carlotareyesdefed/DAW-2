<?php
$alumnos = [
    [
        'nombre' => 'Ana',
        'edad' => 20,
        'nota' => 8
    ],
    [
        'nombre' => 'Carlos',
        'edad' => 22,
        'nota' => 4
    ],
    [
        'nombre' => 'Lucía',
        'edad' => 19,
        'nota' => 7
    ]
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tabla de alumnos</title>
</head>

<body>
    <h1>Resultados de alumnos</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Nota</th>
                <th>Resultado</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= escapar($alumno['nombre']) ?></td>
                    <td><?= $alumno['edad'] ?></td>
                    <td><?= $alumno['nota'] ?></td>

                    <td>
                        <?php if ($alumno['nota'] >= 5): ?>
                            Aprobado
                        <?php else: ?>
                            Suspenso
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>