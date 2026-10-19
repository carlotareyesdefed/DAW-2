<?php
/*
PLANTILLAS DE VISTAS PARA EL EXAMEN - PHP 8+

CÓMO USAR ESTE ARCHIVO
1. Puedes ejecutarlo tal cual: contiene datos ficticios para ver las tablas.
2. Para un ejercicio, copia la tabla que necesites a vistas/resultado.php.
3. El procesador valida, calcula y finalmente carga la vista así:
       require __DIR__ . '/vistas/resultado.php';
   Las variables creadas por el procesador estarán disponibles en la vista.
4. NO copies los datos de demostración a tu vista definitiva: sustituirían
   los resultados que acabas de calcular en el procesador.
5. Conserva una sola cabecera HTML y un solo cierre de página.

Este archivo funciona solo, sin incluir formulario.php.
Usa htmlspecialchars para texto y number_format para números ya validados.
Si has cargado tu biblioteca, puedes usar escapar($texto) y euros($importe).

SINTAXIS PARA MEZCLAR PHP Y HTML
  <?= $variable ?>                          equivale a echo $variable;
  <?php foreach ($lista as $elemento): ?>   abre un bucle;
  <?php endforeach; ?>                     cierra ese bucle;
  <?php if ($condicion): ?>                 abre una condición;
  <?php else: ?>                           alternativa;
  <?php endif; ?>                          cierra la condición.

Las tablas usan:
  thead = títulos de columnas; tbody = datos; tfoot = totales.
  tr = fila; th = encabezado; td = celda de datos.
  colspan="3" = una celda ocupa tres columnas.
*/

// ================= DATOS DE DEMOSTRACIÓN =================
// En el ejercicio real, estas variables las prepara el procesador.
$titulo = 'Plantillas de vistas para PHP';

// 1. Lista de registros (agenda, productos, alumnos...).
$actosVisibles = [
    ['fecha' => '2026-11-02', 'provincia' => 'Granada', 'lugar' => 'Teatro Norte', 'aforo' => 250],
    ['fecha' => '2026-11-03', 'provincia' => 'Sevilla', 'lugar' => 'Centro Cívico', 'aforo' => 90]
];
$totalCoincidencias = 2;
$aforoTotal = 340;

// 2. Resumen: etiquetas asociadas a valores ya preparados para mostrar.
$resumen = [
    'Nombre' => 'Ana García',
    'Edad' => '22 años',
    'Asignaturas seleccionadas' => '3',
    'Horas semanales' => '18 horas'
];

// 3. Ticket: el procesador calcula subtotal, descuento, base, IVA y total.
// En este ejemplo los precios NO incluyen IVA.
$lineasPedido = [
    ['nombre' => 'Kit básico', 'cantidad' => 2, 'precio' => 35.0, 'subtotal' => 70.0],
    ['nombre' => 'Lote de chapas', 'cantidad' => 2, 'precio' => 9.0, 'subtotal' => 18.0]
];
$mercancia = 88.0;
$descuento = 4.4;
$transporte = 12.0;
$base = 95.6;
$iva = 20.076;
$totalPedido = 115.676;
$presupuestoMaximo = 100.0;
$extrasSeleccionados = ['Chapas', 'Pegatinas'];

// 4. Mapa de votos ordenado en el procesador con arsort($votosPartido).
$votosPartido = ['Partido A' => 150, 'Partido B' => 100, 'Partido C' => 50];
// null representa un porcentaje no calculable por ausencia de votos válidos.
$porcentajeVotos = ['Partido A' => 50.0, 'Partido B' => 100 / 3, 'Partido C' => 50 / 3];
$umbralPorcentaje = 5;
$ganadores = ['Partido A'];

// 5. Pactos: el procesador prepara la información de cada alternativa.
$socios = ['Partido A', 'Partido C'];
$escanosPacto = 19;
$mayoria = 21;
$alternativas = [
    'Partido B' => ['escanos' => 12, 'total' => 31, 'mayoria' => true],
    'Partido D' => ['escanos' => 1, 'total' => 20, 'mayoria' => false]
];
$mensajePacto = 'Faltan 2 escaños para alcanzar la mayoría.';

// 6. Corrección de test. El procesador compara con las soluciones del servidor.
$detalleTest = [
    ['pregunta' => '2 + 2', 'respuesta' => '4', 'solucion' => '4', 'correcta' => true],
    ['pregunta' => 'Capital de España', 'respuesta' => 'París', 'solucion' => 'Madrid', 'correcta' => false],
    ['pregunta' => '3 + 3', 'respuesta' => '', 'solucion' => '6', 'correcta' => false]
];
$aciertos = 1;
$fallos = 2; // Incluye las preguntas en blanco.
$sinResponder = 1;
$nota = 3.33;
// ================= FIN DE DATOS DE DEMOSTRACIÓN =================
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Ejemplos ficticios. Copia el bloque que necesites y adapta los nombres de las variables.</p>

    <!-- 1. ARRAY DE REGISTROS: una fila por elemento. -->
    <section>
        <h2>1. Listado de registros: agenda</h2>
        <p>Total de coincidencias: <?= $totalCoincidencias ?></p>
        <p>Aforo de todas las coincidencias: <?= $aforoTotal ?></p>
        <!-- Si hay límite, los totales se calculan ANTES de array_slice. -->
        <?php if ($actosVisibles === []): ?>
            <p>No se han encontrado actos con los filtros seleccionados.</p>
        <?php else: ?>
            <div class="tabla-scroll">
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
            </div>
        <?php endif; ?>
    </section>

    <!-- 2. MAPA ETIQUETA => VALOR: útil para IMC, matrícula o resúmenes. -->
    <section>
        <h2>2. Tabla de resumen</h2>
        <table>
            <caption>Datos del resultado</caption>
            <tbody>
                <?php foreach ($resumen as $etiqueta => $valor): ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') ?></th>
                        <td><?= htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- 3. DETALLE + TOTALES: cuatro columnas; colspan=3 deja una para importe. -->
    <section>
        <h2>3. Ticket o presupuesto</h2>
        <?php if ($lineasPedido === []): ?>
            <p>No hay productos en el pedido.</p>
        <?php else: ?>
            <div>
                <table>
                    <caption>Detalle del pedido</caption>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lineasPedido as $linea): ?>
                            <tr>
                                <td><?= htmlspecialchars($linea['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= $linea['cantidad'] ?></td>
                                <td><?= number_format($linea['precio'], 2, ',', '.') ?> €</td>
                                <td><?= number_format($linea['subtotal'], 2, ',', '.') ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr><th colspan="3">Mercancía</th><td class="numero"><?= number_format($mercancia, 2, ',', '.') ?> €</td></tr>
                        <tr><th colspan="3">Descuento a restar</th><td class="numero"><?= number_format($descuento, 2, ',', '.') ?> €</td></tr>
                        <tr><th colspan="3">Transporte</th><td class="numero"><?= number_format($transporte, 2, ',', '.') ?> €</td></tr>
                        <tr><th colspan="3">Base imponible</th><td class="numero"><?= number_format($base, 2, ',', '.') ?> €</td></tr>
                        <tr><th colspan="3">IVA añadido (21 %)</th><td class="numero"><?= number_format($iva, 2, ',', '.') ?> €</td></tr>
                        <tr><th colspan="3">Total</th><td class="numero"><strong><?= number_format($totalPedido, 2, ',', '.') ?> €</strong></td></tr>
                    </tfoot>
                </table>
            </div>
            <!-- Para Ej2, donde el IVA ya está incluido, cambia el desglose:
                 muestra total e IVA incluido; NO vuelvas a sumarlo. -->
            <?php if ($totalPedido > $presupuestoMaximo): ?>
                <p>El pedido supera el presupuesto de <?= number_format($presupuestoMaximo, 2, ',', '.') ?> €.</p>
            <?php else: ?>
                <p>El pedido está dentro del presupuesto.</p>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Lista opcional: también puedes colocar este bloque dentro de un td. -->
        <h3>Extras seleccionados</h3>
        <?php if ($extrasSeleccionados === []): ?>
            <p>Ninguno.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($extrasSeleccionados as $extra): ?>
                    <li><?= htmlspecialchars($extra, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <!-- 4. MAPA ORDENADO: recorre votosPartido para conservar su orden. -->
    <section>
        <h2>4. Recuento y porcentajes</h2>
        <table>
            <caption>Resultados del escrutinio ficticio</caption>
            <thead>
                <tr>
                    <th>Partido</th>
                    <th>Votos</th>
                    <th>Porcentaje</th>
                    <th>¿Alcanza el umbral?</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($votosPartido === []): ?>
                    <tr><td colspan="4">No hay resultados disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($votosPartido as $partido => $votos): ?>
                        <?php $porcentaje = $porcentajeVotos[$partido] ?? null; ?>
                        <tr>
                            <th><?= htmlspecialchars($partido, ENT_QUOTES, 'UTF-8') ?></th>
                            <td><?= $votos ?></td>
                            <?php if ($porcentaje === null || $porcentaje === false): ?>
                                <td class="numero">No calculable</td>
                                <td>No calculable</td>
                            <?php else: ?>
                                <td class="numero"><?= number_format($porcentaje, 2, ',', '.') ?> %</td>
                                <td><?= $porcentaje >= $umbralPorcentaje ? 'Sí' : 'No' ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($ganadores === []): ?>
            <p>No hay ganador con votos a candidaturas.</p>
        <?php elseif (count($ganadores) > 1): ?>
            <p>Empate entre: <?= htmlspecialchars(implode(', ', $ganadores), ENT_QUOTES, 'UTF-8') ?>.</p>
        <?php else: ?>
            <p>Ganador: <?= htmlspecialchars($ganadores[0], ENT_QUOTES, 'UTF-8') ?>.</p>
        <?php endif; ?>
    </section>

    <!-- 5. ARRAYS ANIDADOS Y BOOLEANOS: alternativas de pactos. -->
    <section>
        <h2>5. Informe de pacto</h2>
        <table>
            <caption>Resumen del pacto ficticio</caption>
            <tbody>
                <tr><th>Socios</th><td><?= htmlspecialchars(implode(', ', $socios), ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><th>Escaños del pacto</th><td><?= $escanosPacto ?></td></tr>
                <tr><th>Mayoría necesaria</th><td><?= $mayoria ?></td></tr>
            </tbody>
        </table>
        <p><?= htmlspecialchars($mensajePacto, ENT_QUOTES, 'UTF-8') ?></p>
        <table>
            <caption>Si se incorpora un socio adicional</caption>
            <thead>
                <tr>
                    <th>Partido externo</th>
                    <th>Aporta</th>
                    <th>Total de la coalición</th>
                    <th>¿Hay mayoría?</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($alternativas === []): ?>
                    <tr><td colspan="4">No hay socios adicionales disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($alternativas as $partido => $datos): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($partido, ENT_QUOTES, 'UTF-8') ?></th>
                            <td><?= $datos['escanos'] ?></td>
                            <td><?= $datos['total'] ?></td>
                            <td><?= $datos['mayoria'] ? 'Sí' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- 6. TEST: resultado por pregunta y resumen final. -->
    <section>
        <h2>6. Corrección de test</h2>
        <table>
            <caption>Detalle de respuestas</caption>
            <thead>
                <tr>
                    <th scope="col">Pregunta</th>
                    <th scope="col">Tu respuesta</th>
                    <th scope="col">Solución</th>
                    <th scope="col">Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($detalleTest === []): ?>
                    <tr><td colspan="4">No hay preguntas que mostrar.</td></tr>
                <?php else: ?>
                    <?php foreach ($detalleTest as $fila): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['pregunta'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $fila['respuesta'] === '' ? 'Sin responder' : htmlspecialchars($fila['respuesta'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($fila['solucion'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="<?= $fila['correcta'] ? 'correcto' : 'incorrecto' ?>">
                                <?= $fila['correcta'] ? 'Acierto' : 'Fallo' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <p>Aciertos: <?= $aciertos ?>. Fallos (incluidos blancos): <?= $fallos ?>. Sin responder: <?= $sinResponder ?>.</p>
        <p><strong>Nota: <?= number_format($nota, 2, ',', '.') ?> / 10</strong></p>
        <!-- Las soluciones se muestran al CORREGIR, no al generar el formulario. -->
    </section>

    <!-- En tu vista real descomenta y cambia el nombre del formulario:
    <p><a href="pedido.html">Volver al formulario</a></p>
    Si el navegador sigue en procesarPedido.php, la URL relativa se resuelve
    desde esa dirección, no desde la carpeta interna vistas/ del servidor.
    -->
</body>
</html>
