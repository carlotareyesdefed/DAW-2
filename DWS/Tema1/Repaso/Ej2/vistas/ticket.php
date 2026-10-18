<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket del pedido</title>
</head>

<body>
    <h1>Ticket de material electoral</h1>
    <!-- TODO: tabla o lista con partido, pack, unidades, extras y transporte. -->
    <!-- TODO: mostrar subtotal, descuento, transporte, base, IVA y total. -->
    <!-- TODO: mensaje distinto dependiendo de si se superan los 500 €. -->

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
                <td><?= escapar($pack) ?></td>
                <td><?= $unidades ?></td>
                <td><?php foreach ($extrasSeleccionados as $extra): ?>
                        <li><?= escapar($extra = $extras[$extra]['nombre']) ?></li>
                    <?php endforeach; ?>
                </td>
                <td><?= escapar($transporte) ?></td>
            </tr>
        </tbody>

        <tfoot>
            <td>Mercancía: <?= euros($mercancia) ?></td>
            <td>Descuento: <?php if (is_numeric($descuento)):
                echo euros($descuento); ?>
                <?php else:
                echo escapar($descuento) ?>
                <?php endif; ?>
            </td>
            <td>Transporte: <?= euros($transportes[$transporte]) ?></td>
            <td>Precio base: <?= euros($precioBase) ?></td>
            <td>IVA: <?= euros($iva) ?></td>
            <td>Total: <?= euros($precioTotal) ?> <?= escapar($mensaje) ?></td>
        </tfoot>

    </table>


    <p><a href="pedido.html">Volver al formulario</a></p>
</body>

</html>