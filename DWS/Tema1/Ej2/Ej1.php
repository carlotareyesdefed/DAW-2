<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Covirán</title>
</head>

<body>
    <h1>Ticket de compra</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Precio ud.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Datos iniciales: productos y precios CON el IVA del 21 % ya incluido.
            $productos = [
                'Maritoñi' => 2.50,
                'Puleva de fresa' => 1.75,
                'Pipas granaínas' => 0.75,
                'Alhambra roja' => 1.25,
                'Salailla' => 0.50,
            ];
            // Cada cantidad corresponde al producto de la misma posición.
            $cantidadesCompradas = [2, 2, 1, 0, 0];

            $indice = 0;
            $total = 0;

            foreach ($productos as $producto => $precio) {
                $cantidad = $cantidadesCompradas[$indice];

                if ($cantidad > 0) {
                    $subtotal = $precio * $cantidad;
                }
                $total += $subtotal;

                //Mostramos la fila de los productos de los que se ha comprado alguna unidad
                if ($cantidad > 0) {
                    echo "<tr>";
                    echo "<td>$cantidad</td>";
                    echo "<td>$producto</td>";
                    echo "<td>" . number_format($precio, 2, ',') . " €</td>";
                    echo "<td>" . number_format($subtotal, 2, ',') . " €</td>";
                    echo "</tr>";
                }

                $indice++;
            }

            // Calculamos el IVA incluido en el precio final. Si el precio ya incluye un 21 % de IVA:  
            // IVA = total * 21 / 121 
            $iva = round($total * 21 / 121, 2);

            // Mostramos el total 
            echo "<tr>";
            echo "<th colspan='3'>Total</th>";
            echo "<th>" . number_format($total, 2, ',') . " €</th>";
            echo "</tr>";
            // Mostramos el IVA incluido 
            echo "<tr>";
            echo "<th colspan='3'>IVA incluido (21 %)</th>";
            echo "<th>" . number_format($iva, 2, ',') . " €</th>";
            echo "</tr>";

            // TODO 1: recorre los productos y enlaza cada precio con su cantidad.
            // TODO 2: calcula el subtotal de cada producto y acumula el total.
            // TODO 3: muestra únicamente los productos de los que se ha comprado alguna unidad.
            // TODO 4: muestra la fila TOTAL y calcula el IVA incluido en ese total.
            ?>
        </tbody>
    </table>
</body>

</html>