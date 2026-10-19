# Funciones para tu examen de PHP

La copia `formulario_ampliado.php` conserva las 7 funciones de tu formulario y añade 18 funciones. Una de ellas, `calcularMayoria`, ya estaba en el ejercicio de pactos y ahora queda reunida con las demás. No se ha modificado el original.

He revisado los 27 archivos PHP, los 10 HTML, los 2 enunciados y las 47 páginas del PDF incluidos en el ZIP. No contiene Ej6. Las ampliaciones del PDF más relacionadas son 4.6 (arrays), 6.4–6.6 (funciones, archivos y cálculos) y 7.5–7.6 (validación y tests). Las funciones siguen el estilo de tus ejercicios: `if`, `foreach`, arrays y `return`, sin clases.

## Por dónde empezar

1. **Validar opciones, claves y selecciones**: aparece en datos personales, matrícula, agenda, pedido, test y pactos.
2. **Validar mapas de enteros y calcular porcentajes**: especialmente útil para escrutinios y cualquier recuento.
3. **Filtrar, acumular y limitar**: agenda y catálogos.
4. **Distinguir IVA incluido de IVA añadido**: tus dos ejercicios usan supuestos diferentes.
5. **Revisar índices y preguntas sin contestar**: para el generador de tests.

Esto es una prioridad de repaso basada en tus archivos, no una predicción del contenido del examen.

## Cómo incluir el archivo

Pon la copia junto al procesador nuevo y escribe:

```php
require_once __DIR__ . '/formulario_ampliado.php';
exigirMetodo(); // POST por defecto; para la agenda: exigirMetodo('GET');
```

Si la guardas en Tema1 y el procesador está en Repaso/Ej2, la ruta será `__DIR__ . '/../../formulario_ampliado.php'`.

**Carga una sola biblioteca que defina cada función.** La copia contiene también las funciones originales: no cargues simultáneamente el formulario antiguo ni otros archivos que vuelvan a definir `euros`, `escapar` o `calcularMayoria`. `require_once` evita cargar dos veces el mismo archivo; no evita definiciones repetidas en archivos diferentes. Los archivos de datos sí se pueden seguir incluyendo.

La biblioteca requiere PHP 8 o superior, como tu original. Incluirla no imprime nada: el procesador debe llamar a sus funciones. Las nuevas validaciones reciben el **valor**, no el nombre del campo; así sirven tanto para GET como para POST. Tus `validarEntero` y `validarDecimal` originales siguen recibiendo el nombre y leyendo POST.

## Qué función usar

| Función | Cuándo usarla | Resultado |
|---|---|---|
| `exigirMetodo('POST')` | Antes de procesar cualquier formulario | Detiene la ejecución si el método no coincide |
| `validarValorTexto($valor, 1, 20)` | Nombre/apellidos; admite mínimo 0 para texto opcional | Texto sin espacios exteriores o `false` |
| `validarValorDecimal($valor, 0, 500)` | Decimal de GET o de un array | Número o `false` |
| `validarValorOpcion($valor, $lista)` | Radio/select con lista de valores | Opción o `false` |
| `validarValorClave($valor, $catalogo)` | Pack, transporte, escenario, asignatura del test | Clave o `false` |
| `validarSeleccion($valores, $permitidas, $min, $max)` | Aficiones, asignaturas, extras, socios | Lista de textos válidos o `false` |
| `validarMapaEnteros($datos, $claves, 0, $max)` | `votos[PSOE]`, cantidades por producto | Mapa con exactamente las claves exigidas y valores enteros, o `false` |
| `calcularPorcentaje($parte, $total)` | Participación o votos | Porcentaje sin redondear; `false` si total <= 0 |
| `importePorcentaje($base, $tasa)` | Descuento o recargo | Importe de ese porcentaje |
| `desglosarIvaIncluido($total, 21)` | Ticket de Ej2 | Array con `base`, `iva`, `total` |
| `agregarIva($base, 21)` | Pedido de Repaso/Ej2 | Array con `base`, `iva`, `total` |
| `calcularMayoria($totalEscanos)` | Pactos | Mitad entera + 1 |
| `clavesConMaximo($mapa)` | Ganadores y empates | Todas las claves con valor máximo; `[]` si vacío |
| `filtrarRegistros($registros, $criterios)` | Agenda con varios filtros simultáneos | Registros coincidentes, en su orden original |
| `sumarColumna($registros, 'aforo')` | Total de aforos o precios | Suma numérica; 0 si no hay registros |
| `duracionHoras('08:30', '10:00')` | Tramos de matrícula | 1.5; `false` para hora inválida o fin anterior |
| `elegirClavesAleatorias($mapa, $cantidad)` | Generador de tests | Array de claves distintas o `false` |
| `corregirTest($preguntas, $respuestas, $opciones, $soluciones)` | Corrector de Ej7 | Resumen de nota y recuentos o `false` |

**Regla común: comprueba `=== false`.** `0` votos, el transporte de 0 €, el texto opcional `''` y una selección vacía `[]` pueden ser válidos. No uses `if (!$dato)` para distinguirlos de un error.

Las funciones de cálculo reciben números ya validados. Usa importes no negativos, descuentos entre 0 y 100 y un total de escaños positivo. `sumarColumna` y `filtrarRegistros` se aplican a los datos estructurados del servidor, no directamente a POST. Los decimales usan punto, como en tus ejercicios.

## 1. Nombre, radio y catálogo

```php
$nombre = validarValorTexto($_POST['nombre'] ?? null, 1, 20);
$sexo = validarValorOpcion($_POST['sexo'] ?? null, ['femenino', 'masculino']);
if ($nombre === false || $sexo === false) {
    exit('Nombre o sexo incorrectos.');
}
echo '<p>' . escapar($nombre) . '</p>';
```

Para un catálogo como `$packs = ['basico' => ['precio' => 35], ...]`:

```php
$pack = validarValorClave($_POST['pack'] ?? null, $packs);
if ($pack === false) {
    exit('Pack incorrecto.');
}
$precioPack = $packs[$pack]['precio'];
```

`in_array` busca un **valor** en una lista. `array_key_exists` busca una **clave** en un mapa. No conviertas un array recibido a texto para validarlo; comprueba primero el tipo, como hacen estas funciones.

## 2. Checkbox: pactos y extras

Para pactos, valida primero el escenario y después sus socios:

```php
$escenario = validarValorClave($_POST['escenario'] ?? null, $escenarios);
if ($escenario === false) {
    exit('Escenario incorrecto.');
}
$partidosEscenario = $escenarios[$escenario]['partidos'];
$socios = validarSeleccion(
    $_POST['socios'] ?? [], array_keys($partidosEscenario), 1, 3
);
if ($socios === false) {
    exit('Elige entre 1 y 3 socios válidos y sin repetir.');
}
$escanosPacto = 0;
foreach ($socios as $socio) {
    $escanosPacto += $partidosEscenario[$socio];
}
$mayoria = calcularMayoria($escenarios[$escenario]['escanos']);
$faltan = max(0, $mayoria - $escanosPacto);
$sobran = max(0, $escanosPacto - $mayoria);
```

Para extras, ninguna selección es válida y los duplicados se eliminan:

```php
$seleccion = validarSeleccion(
    $_POST['extras'] ?? [], array_keys($extras), 0, null, false
);
if ($seleccion === false) {
    exit('Extras incorrectos.');
}
$sumaExtras = 0;
foreach ($seleccion as $extra) {
    $sumaExtras += $extras[$extra]['precio'];
}
```

En aficiones usa directamente `$aficionesValidas`, sin `array_keys`. Esta función devuelve índices consecutivos: **no la uses para reindexar las preguntas de un test**, cuyos índices están vinculados a las respuestas.

## 3. Escrutinio: enteros, porcentajes y empates

```php
$censo = validarEntero('censo', 1, 100000);
if ($censo === false) {
    exit('Censo incorrecto.');
}
$votos = validarMapaEnteros($_POST['votos'] ?? null, $partidos, 0, $censo);
$blancos = validarEntero('blancos', 0, $censo);
$nulos = validarEntero('nulos', 0, $censo);
if ($votos === false || $blancos === false || $nulos === false) {
    exit('Recuento incorrecto.');
}
$validos = array_sum($votos) + $blancos;
$emitidos = $validos + $nulos;
if ($emitidos < 1 || $emitidos > $censo) {
    exit('Los emitidos deben estar entre 1 y el censo.');
}
$participacion = calcularPorcentaje($emitidos, $censo);
arsort($votos, SORT_NUMERIC); // Conserva las claves de los partidos.
foreach ($votos as $partido => $cantidad) {
    $porcentaje = calcularPorcentaje($cantidad, $validos);
    echo '<p>' . escapar($partido) . ': ';
    echo $porcentaje === false
        ? 'Sin votos válidos'
        : number_format($porcentaje, 2, ',', '.') . ' %';
    echo '</p>';
}
$ganadores = array_sum($votos) > 0 ? clavesConMaximo($votos) : [];
```

Si solo hay votos nulos, hay emitidos pero no válidos. No se puede dividir entre cero. Si todas las candidaturas tienen 0 votos, conviene indicar que no hay votos a candidaturas; la función de máximo, por sí sola, devolvería todos empatados a 0. Para el umbral compara el porcentaje sin redondear con `$umbralPorcentaje`; redondea únicamente al mostrarlo.

## 4. Agenda: filtrar antes de limitar

```php
exigirMetodo('GET');
$provincia = validarValorOpcion(
    $_GET['provincia'] ?? null, array_merge(['Todas'], $provincias)
);
$partido = validarValorOpcion(
    $_GET['partido'] ?? null, array_merge(['Todos'], $partidos)
);
$tipo = validarValorOpcion(
    $_GET['tipo'] ?? null, array_merge(['Todos'], $tipos)
);
$limite = validarValorEntero($_GET['limite'] ?? null, 1, 10);
if ($provincia === false || $partido === false || $tipo === false || $limite === false) {
    exit('Filtros incorrectos.');
}
$criterios = [];
if ($provincia !== 'Todas') $criterios['provincia'] = $provincia;
if ($partido !== 'Todos') $criterios['partido'] = $partido;
if ($tipo !== 'Todos') $criterios['tipo'] = $tipo;
$filtrados = filtrarRegistros($actos, $criterios);
$totalCoincidencias = count($filtrados);
$aforoTotal = sumarColumna($filtrados, 'aforo');
$visibles = array_slice($filtrados, 0, $limite);
```

Los totales corresponden a **todas las coincidencias**, no solo a las filas visibles. Un array de criterios vacío devuelve todos los registros.

## 5. Descuentos e IVA

Ticket de Ej2, con tus cantidades: 2 × 2.50 + 2 × 1.75 + 1 × 0.75 = **9.25 €**, IVA ya incluido.

```php
$ticket = desglosarIvaIncluido(9.25);
echo euros($ticket['total']); // 9,25 €
echo euros($ticket['iva']);   // 1,61 €
```

Pedido de Repaso/Ej2: pack básico 35 €, chapas 9 €, dos unidades, código LOCAL2026 (5 %) y transporte normal 12 €:

```php
$mercancia = (35 + 9) * 2;                    // 88
$descuento = importePorcentaje($mercancia, 5); // 4.40
$base = $mercancia - $descuento + 12;          // 95.60
$pedido = agregarIva($base);                  // IVA 20.076; total 115.676
echo euros($pedido['total']);                 // 115,68 €
```

El transporte se suma una vez, después del descuento sobre mercancía, igual que en tu ejercicio. Las funciones conservan precisión interna; usa `euros()` para mostrar. Si el enunciado exige redondear cada concepto, aplica `round(..., 2)` en esos pasos. `number_format` devuelve texto: no lo uses para seguir calculando.

## 6. Horarios y tests

```php
$horas = duracionHoras('08:30', '10:00'); // 1.5
```

Recorre los tramos de matrícula de dos en dos y suma esta duración. La función admite duración 0 y rechaza fin anterior al inicio. No modela un cambio de día.

Para generar el test, valida la asignatura y cantidad antes de elegir:

```php
$asignatura = validarValorClave($_POST['asignatura'] ?? null, $repositorioPreguntas);
$cantidad = validarEntero('numeroDePreguntas', 1, 5);
if ($asignatura === false || $cantidad === false) {
    exit('Configuración incorrecta.');
}
$seleccionadas = elegirClavesAleatorias($repositorioPreguntas[$asignatura], $cantidad);
if ($seleccionadas === false) exit('No hay suficientes preguntas.');
```

Para corregir, con los dos repositorios cargados:

```php
$asignatura = validarValorClave($_POST['asignatura'] ?? null, $repositorioPreguntas);
if ($asignatura === false || !array_key_exists($asignatura, $repositorioRespuestas)) {
    exit('Asignatura incorrecta.');
}
$resultado = corregirTest(
    $_POST['preguntas'] ?? null,
    $_POST['respuestas'] ?? [],
    $repositorioPreguntas[$asignatura],
    $repositorioRespuestas[$asignatura]
);
if ($resultado === false) exit('Test incorrecto.');
echo 'Nota: ' . number_format($resultado['nota'], 2, ',', '.') . ' / 10';
echo '<p>Fallos, incluidos blancos: ' . $resultado['fallos'] . '</p>';
```

Valida de 1 a 5 preguntas, su pertenencia al repositorio, duplicados, opciones e índices de respuesta adicionales. Conserva los índices enviados para relacionar pregunta y respuesta. Un test de 5 preguntas con 3 aciertos, 1 error y 1 sin responder da **6/10, 2 fallos y 1 sin responder**. No se utiliza el campo oculto `cantidad` para contar.

Como en tu práctica, esto comprueba que las preguntas pertenecen al repositorio; para demostrar que son exactamente las generadas habría que guardar la selección en el servidor. El código no afirma ofrecer esa garantía.

## Recordatorio final de trabajo

Recibir → validar tipo, rango y opciones → calcular → mostrar con escape HTML.

Escapa el texto al imprimirlo, no antes de compararlo con el catálogo. Usa `=== false` con las validaciones. Distingue clave de valor, verifica denominadores y conserva las claves al ordenar mapas con `arsort`. Antes del examen, prueba los casos de 0 votos, ninguna casilla, un duplicado, un campo enviado como array y un test con preguntas en blanco.

## Verificación de la copia

Sintaxis comprobada y 39 pruebas ejecutadas correctamente con PHP 8.5.11: textos Unicode, valores ausentes o con tipo incorrecto, duplicados, límites de selección, mapas de votos, división entre cero, empate, IVA de ambos ejercicios, agenda con datos reales del archivo, tramos horarios y corrección de tests. Se ha verificado que las 7 funciones originales permanecen intactas al inicio del archivo. Las pruebas corresponden a la biblioteca ampliada, no a una ejecución de todas las pantallas originales en el navegador.
