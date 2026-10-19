<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket del pedido</title>
</head>

<body>
    <h1>Ticket de material electoral</h1>

    <h2>Datos del pedido</h2>

    <table border="1">

        <thead>
            <tr>
                <th>Partido</th>
                <th>Pack</th>
                <th>Unidades</th>
                <th>Extras</th>
                <th>Transporte</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td><?= escapar($partido) ?></td>

                <td><?= escapar($packs[$pack]['nombre']) ?></td>

                <td><?= $unidades ?></td>

                <td>
                    <?php if ($extrasSeleccionados === []): ?>
                        Ninguno
                    <?php else: ?>
                        <ul>
                            <?php foreach ($extrasSeleccionados as $extra): ?>
                                <li><?= escapar($extras[$extra]['nombre']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </td>

                <td><?= escapar(ucfirst($transporte)) ?></td>
            </tr>
        </tbody>

    </table>

    <h2>Desglose del presupuesto</h2>

    <table border="1">

        <tbody>
            <tr>
                <th>Precio del pack por unidad</th>
                <td><?= euros($precioPack) ?></td>
            </tr>

            <tr>
                <th>Precio de los extras por unidad</th>
                <td><?= euros($sumaExtras) ?></td>
            </tr>

            <tr>
                <th>Precio unitario</th>
                <td><?= euros($precioUnitario) ?></td>
            </tr>

            <tr>
                <th>Unidades</th>
                <td><?= $unidades ?></td>
            </tr>

            <tr>
                <th>Mercancía sin descuento</th>
                <td><?= euros($mercancia) ?></td>
            </tr>

            <tr>
                <th>Descuento aplicado</th>
                <td><?= euros($descuento) ?></td>
            </tr>

            <tr>
                <th>Mercancía con descuento</th>
                <td><?= euros($mercanciaDescontada) ?></td>
            </tr>

            <tr>
                <th>Transporte</th>
                <td><?= euros($transportes[$transporte]) ?></td>
            </tr>

            <tr>
                <th>Base imponible</th>
                <td><?= euros($precioBase) ?></td>
            </tr>

            <tr>
                <th>IVA (21 %)</th>
                <td><?= euros($iva) ?></td>
            </tr>

            <tr>
                <th>Total del pedido</th>
                <td><strong><?= euros($precioTotal) ?></strong></td>
            </tr>
        </tbody>

    </table>

    <h2>Información adicional</h2>

    <?php if ($mensajeCodigo !== ''): ?>
        <p><?= escapar($mensajeCodigo) ?></p>
    <?php endif; ?>

    <?php if ($precioTotal > $presupuestoMaximo): ?>
        <p>El precio total supera el presupuesto máximo de <?= euros($presupuestoMaximo) ?>.</p>
    <?php else: ?>
        <p>El precio total no supera el presupuesto máximo de <?= euros($presupuestoMaximo) ?>.</p>
    <?php endif; ?>

    <p><a href="pedido.html">Volver al formulario</a></p>

</body>

</html>