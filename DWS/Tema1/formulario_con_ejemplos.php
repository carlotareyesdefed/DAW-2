<?php

function validarEntero(string $campo, int $min, ?int $max = null): int|false
{
    $opciones = ['min_range' => $min];

    if ($max !== null) {
        $opciones['max_range'] = $max;
    }

    return filter_var(
        $_POST[$campo] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => $opciones]
    );
}

/* EJEMPLO DE USO: validarEntero

// Formulario: <input type="number" name="edad">
// Si llega $_POST['edad'] = '25', devuelve el entero 25.
$edad = validarEntero('edad', 0, 130);
if ($edad === false) {
    exit('La edad debe ser un entero entre 0 y 130.');
}
echo $edad;
// Sin máximo: validarEntero('cantidad', 1);

FIN DEL EJEMPLO */

function validarValorEntero($valor, int $min, ?int $max = null): int|false
{
    $opciones = ['min_range' => $min];

    if ($max !== null) {
        $opciones['max_range'] = $max;
    }

    return filter_var(
        $valor,
        FILTER_VALIDATE_INT,
        ['options' => $opciones]
    );
}

/* EJEMPLO DE USO: validarValorEntero

// Recibe el VALOR, no el nombre de un campo. También sirve para GET.
$limite = validarValorEntero('5', 1, 10);
if ($limite === false) {
    exit('Límite incorrecto.');
}
echo $limite; // 5
// Con un formulario: validarValorEntero($_GET['limite'] ?? null, 1, 10);
// validarValorEntero('hola', 1, 10) devuelve false.
// validarValorEntero('0', 0) devuelve 0, que es válido.

FIN DEL EJEMPLO */
function validarDecimal(string $campo, float $min, float $max): float|false
{
    $valor = filter_var(
        $_POST[$campo] ?? null,
        FILTER_VALIDATE_FLOAT
    );

    if ($valor !== false && $valor >= $min && $valor <= $max) {
        return $valor;
    }

    return false;
}

/* EJEMPLO DE USO: validarDecimal

// Formulario: <input type="number" name="peso" step="0.1">
// Si llega $_POST['peso'] = '65.5', devuelve 65.5.
$peso = validarDecimal('peso', 20, 500);
if ($peso === false) {
    exit('El peso debe estar entre 20 y 500.');
}
echo $peso;

FIN DEL EJEMPLO */

function imprimirCabecera(string $titulo): void
{
    echo '<!doctype html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '<meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '</title>';
    echo '</head>';
    echo '<body>';
}

/* EJEMPLO DE USO: imprimirCabecera

// Al comenzar a mostrar la página, después de validar los datos:
imprimirCabecera('Resultado del pedido');
echo '<h1>Tu pedido</h1>';
// Aquí iría el contenido de la página.
imprimirPie();

FIN DEL EJEMPLO */

function imprimirPie(): void
{
    echo '</body>';
    echo '</html>';
}

/* EJEMPLO DE USO: imprimirPie

// Al terminar el contenido, cierra body y html:
imprimirCabecera('Resultados');
echo '<p>Proceso terminado.</p>';
imprimirPie();

FIN DEL EJEMPLO */

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/* EJEMPLO DE USO: escapar

$nombre = '<b>Ana</b>';
echo '<p>' . escapar($nombre) . '</p>';
// El navegador muestra literalmente <b>Ana</b>, sin poner Ana en negrita.
// Úsala al mostrar texto dentro de HTML, después de validarlo.

FIN DEL EJEMPLO */

function euros(float $numero): string {
    return number_format($numero, 2, ',', '.') . ' €';
}

/* EJEMPLO DE USO: euros

$total = 1234.5;
echo euros($total); // 1.234,50 €
// Devuelve texto para mostrar; sigue calculando con $total, no con euros($total).

FIN DEL EJEMPLO */
// FUNCIONES ADICIONALES PARA TEMA 1
 

// Usar antes de imprimir HTML. Ejemplo: exigirMetodo('GET');
function exigirMetodo(string $metodo = 'POST'): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($metodo)) {
        http_response_code(405);
        exit('Método de envío incorrecto. Accede desde el formulario.');
    }
}

/* EJEMPLO DE USO: exigirMetodo

// Antes de imprimir HTML, en un procesador de formularios POST:
exigirMetodo(); // Equivale a exigirMetodo('POST').
// Si la petición no es POST, detiene el programa con un mensaje.
// Para el procesador de la agenda, usa en su lugar:
// exigirMetodo('GET');

FIN DEL EJEMPLO */

// Nombre, apellidos o código opcional (min = 0). Cuenta caracteres UTF-8.
function validarValorTexto($valor, int $min = 1, int $max = 80): string|false
{
    if (!is_string($valor)) {
        return false;
    }
    $valor = trim($valor);
    $longitud = preg_match_all('/./us', $valor);
    if ($longitud === false || $longitud < $min || $longitud > $max) {
        return false;
    }
    return $valor;
}

/* EJEMPLO DE USO: validarValorTexto

$nombre = validarValorTexto('  María  ', 1, 20);
if ($nombre === false) {
    exit('El nombre debe tener entre 1 y 20 caracteres.');
}
echo escapar($nombre); // María, sin espacios exteriores.
// Con POST: validarValorTexto($_POST['nombre'] ?? null, 1, 20);
// validarValorTexto('   ', 1, 20) devuelve false.
// validarValorTexto('', 0, 20) devuelve '' (texto opcional válido).

FIN DEL EJEMPLO */

// Complementa validarDecimal(), también para GET y elementos de arrays.
function validarValorDecimal($valor, float $min, ?float $max = null): float|false
{
    if (!is_string($valor) && !is_int($valor) && !is_float($valor)) {
        return false;
    }
    $numero = filter_var($valor, FILTER_VALIDATE_FLOAT);
    if ($numero === false || $numero < $min ||
        ($max !== null && $numero > $max)) {
        return false;
    }
    return $numero;
}

/* EJEMPLO DE USO: validarValorDecimal

$precio = validarValorDecimal('12.50', 0, 100);
if ($precio === false) {
    exit('Precio incorrecto.');
}
echo euros($precio); // 12,50 €
// Sin máximo: validarValorDecimal('1500', 0) devuelve 1500.0.
// Con GET: validarValorDecimal($_GET['precio'] ?? null, 0, 100);
// Usa punto decimal: '12.50', no '12,50'.

FIN DEL EJEMPLO */

// Radio/select cuyo valor pertenece a una LISTA de textos.
function validarValorOpcion($valor, array $permitidas): string|false
{
    return is_string($valor) && in_array($valor, $permitidas, true)
        ? $valor : false;
}

/* EJEMPLO DE USO: validarValorOpcion

$permitidas = ['mañana', 'tarde'];
$turno = validarValorOpcion('mañana', $permitidas);
if ($turno === false) {
    exit('Turno incorrecto.');
}
echo escapar($turno); // mañana
// Con POST: validarValorOpcion($_POST['turno'] ?? null, $permitidas);
// validarValorOpcion('noche', $permitidas) devuelve false.

FIN DEL EJEMPLO */

// Radio/select cuyo valor es una CLAVE de un catálogo asociativo.
function validarValorClave($valor, array $catalogo): string|false
{
    return is_string($valor) && array_key_exists($valor, $catalogo)
        ? $valor : false;
}

/* EJEMPLO DE USO: validarValorClave

$transportes = ['recogida' => 0, 'normal' => 12, 'urgente' => 28];
$transporte = validarValorClave('normal', $transportes);
if ($transporte === false) {
    exit('Transporte incorrecto.');
}
$precioTransporte = $transportes[$transporte];
echo euros($precioTransporte); // 12,00 €
// Devuelve 'normal' (la clave), no 12 (el precio).
// Con POST: validarValorClave($_POST['transporte'] ?? null, $transportes);
// validarValorClave('avion', $transportes) devuelve false.

FIN DEL EJEMPLO */

// Checkbox: recibe una lista de valores permitidos (array_keys para catálogos).
// Rechaza duplicados por defecto (pactos). Con false, los elimina (extras).
// Los límites min/max se aplican al número de opciones distintas.
function validarSeleccion(
    $valores, array $permitidas, int $min = 0, ?int $max = null,
    bool $rechazarDuplicados = true
): array|false {
    if (!is_array($valores)) {
        return false;
    }
    $resultado = [];
    foreach ($valores as $valor) {
        if (!is_string($valor) || !in_array($valor, $permitidas, true)) {
            return false;
        }
        if (in_array($valor, $resultado, true)) {
            if ($rechazarDuplicados) {
                return false;
            }
            continue;
        }
        $resultado[] = $valor;
    }
    if (count($resultado) < $min ||
        ($max !== null && count($resultado) > $max)) {
        return false;
    }
    return $resultado;
}

/* EJEMPLO DE USO: validarSeleccion

// Un checkbox con name="socios[]" envía un array de textos.
$permitidos = ['PSOE', 'PP', 'Vox', 'Sumar', 'Podemos'];
$socios = validarSeleccion(['PSOE', 'Sumar'], $permitidos, 1, 3);
if ($socios === false) {
    exit('Elige de 1 a 3 socios válidos y sin repetir.');
}
echo escapar(implode(', ', $socios)); // PSOE, Sumar
// Con POST: validarSeleccion($_POST['socios'] ?? [], $permitidos, 1, 3);
// ['PSOE', 'PSOE'] se rechaza porque hay un duplicado.

// Para extras opcionales, podemos eliminar duplicados en vez de rechazarlos:
$extras = ['chapas' => 9, 'pegatinas' => 6];
$seleccion = validarSeleccion(
    ['chapas', 'chapas'], array_keys($extras), 0, null, false
);
// $seleccion será ['chapas']. Con [] devuelve []: ninguna selección válida.

FIN DEL EJEMPLO */

// votos[PSOE], votos[PP]... Exige exactamente las claves esperadas.
// Devuelve ENTEROS, no las cadenas originales recibidas del formulario.
function validarMapaEnteros(
    $datos, array $claves, int $min = 0, ?int $max = null
): array|false {
    if (!is_array($datos) || count($datos) !== count($claves)) {
        return false;
    }
    $resultado = [];
    foreach ($claves as $clave) {
        if (!array_key_exists($clave, $datos)) {
            return false;
        }
        $valor = $datos[$clave];
        if (!is_string($valor) && !is_int($valor)) {
            return false;
        }
        $numero = validarValorEntero($valor, $min, $max);
        if ($numero === false) {
            return false;
        }
        $resultado[$clave] = $numero;
    }
    return $resultado;
}

/* EJEMPLO DE USO: validarMapaEnteros

$partidos = ['PSOE', 'PP', 'Vox'];
$recibidos = ['PSOE' => '100', 'PP' => '80', 'Vox' => '0'];
$votos = validarMapaEnteros($recibidos, $partidos, 0, 1000);
if ($votos === false) {
    exit('Faltan partidos, sobran claves o hay votos incorrectos.');
}
// $votos = ['PSOE' => 100, 'PP' => 80, 'Vox' => 0];
echo array_sum($votos); // 180
// Con POST: validarMapaEnteros($_POST['votos'] ?? null, $partidos, 0, 1000);
// Si falta 'Vox', devuelve false, aunque los otros votos sean correctos.

FIN DEL EJEMPLO */

// Participación y porcentajes de votos. No redondea antes de comparar umbrales.
// false significa que el porcentaje no está definido (total <= 0).
function calcularPorcentaje(float $parte, float $total): float|false
{
    if ($total <= 0) {
        return false;
    }
    return $parte * 100 / $total;
}

/* EJEMPLO DE USO: calcularPorcentaje

$participacion = calcularPorcentaje(750, 1000);
if ($participacion === false) {
    echo 'No se puede calcular el porcentaje.';
} else {
    echo number_format($participacion, 2, ',', '.') . ' %'; // 75,00 %
}
// calcularPorcentaje(0, 100) devuelve 0.0: válido.
// calcularPorcentaje(0, 0) devuelve false: no se divide entre cero.

FIN DEL EJEMPLO */

// Importe de un descuento o un recargo; NO devuelve el precio descontado.
// La función devuelve el importe del descuento
function importePorcentaje(float $base, float $porcentaje): float
{
    return $base * $porcentaje / 100;
}

/* EJEMPLO DE USO: importePorcentaje

$mercancia = 100;
$descuento = importePorcentaje($mercancia, 10); // 10
$precioFinal = $mercancia - $descuento;        // 90
echo euros($precioFinal); // 90,00 €
// La función devuelve el IMPORTE del descuento, no el precio final.

FIN DEL EJEMPLO */

// Ej2: el precio YA contiene IVA. Usar con total y tasa no negativos.
function desglosarIvaIncluido(float $total, float $tipo = 21): array
{
    $base = $total / (1 + $tipo / 100);
    return ['base' => $base, 'iva' => $total - $base, 'total' => $total];
}

/* EJEMPLO DE USO: desglosarIvaIncluido

// Los 121 € YA incluyen el IVA del 21 %.
$desglose = desglosarIvaIncluido(121, 21);
echo euros($desglose['base']);  // 100,00 €
echo euros($desglose['iva']);   // 21,00 €
echo euros($desglose['total']); // 121,00 €
// No vuelvas a sumar el IVA al total.

FIN DEL EJEMPLO */

// Repaso/Ej2: el precio NO contiene IVA. Usar base y tasa no negativas.
function agregarIva(float $base, float $tipo = 21): array
{
    $iva = importePorcentaje($base, $tipo);
    return ['base' => $base, 'iva' => $iva, 'total' => $base + $iva];
}

/* EJEMPLO DE USO: agregarIva

// Los 100 € son el precio SIN IVA.
$desglose = agregarIva(100, 21);
echo euros($desglose['base']);  // 100,00 €
echo euros($desglose['iva']);   // 21,00 €
echo euros($desglose['total']); // 121,00 €

FIN DEL EJEMPLO */

// Ya aparece en Repaso/Ej4; se centraliza aquí. totalEscanos debe ser > 0.
function calcularMayoria(int $totalEscanos): int
{
    return intdiv($totalEscanos, 2) + 1;
}

/* EJEMPLO DE USO: calcularMayoria

$mayoria = calcularMayoria(41); // 21
$escanosPacto = 23;
if ($escanosPacto >= $mayoria) {
    echo 'El pacto alcanza la mayoría.';
} else {
    echo 'Faltan ' . ($mayoria - $escanosPacto) . ' escaños.';
}
// calcularMayoria(40) también devuelve 21.

FIN DEL EJEMPLO */

// Mapa de números ya validados: devuelve todas las claves empatadas arriba.
function clavesConMaximo(array $datos): array
{
    if ($datos === []) {
        return [];
    }
    $maximo = max($datos);
    $claves = [];
    foreach ($datos as $clave => $valor) {
        if ($valor == $maximo) {
            $claves[] = $clave;
        }
    }
    return $claves;
}

/* EJEMPLO DE USO: clavesConMaximo

$votos = ['PSOE' => 100, 'PP' => 100, 'Vox' => 40];
$ganadores = clavesConMaximo($votos);
// $ganadores = ['PSOE', 'PP']; incluye a todos los empatados.
echo escapar(implode(', ', $ganadores)); // PSOE, PP
// Con [] devuelve []. Con todos a 0 devuelve todos empatados a 0.

FIN DEL EJEMPLO */

// Agenda: criterios ['provincia' => 'Granada', 'tipo' => 'Debate'].
// No incluir los criterios con valor Todos/Todas. Mantiene el orden original.
// Recibe registros del servidor y criterios previamente validados.
function filtrarRegistros(array $registros, array $criterios): array
{
    $resultado = [];
    foreach ($registros as $registro) {
        $coincide = true;
        foreach ($criterios as $campo => $valor) {
            if (!array_key_exists($campo, $registro) || $registro[$campo] !== $valor) {
                $coincide = false;
                break;
            }
        }
        if ($coincide) {
            $resultado[] = $registro;
        }
    }
    return $resultado;
}

/* EJEMPLO DE USO: filtrarRegistros

$actos = [
    ['provincia' => 'Granada', 'tipo' => 'Debate', 'aforo' => 100],
    ['provincia' => 'Sevilla', 'tipo' => 'Debate', 'aforo' => 200],
    ['provincia' => 'Granada', 'tipo' => 'Mitin', 'aforo' => 300]
];
$criterios = ['provincia' => 'Granada', 'tipo' => 'Debate'];
$filtrados = filtrarRegistros($actos, $criterios);
// Devuelve solo el primer acto: debe cumplir AMBOS criterios.
echo count($filtrados); // 1
// filtrarRegistros($actos, []) devuelve todos los actos.
// Si el usuario elige Todos/Todas, no añadas ese campo a los criterios.

FIN DEL EJEMPLO */

// Aforo, precios... Registros internos con una columna numérica existente.
function sumarColumna(array $registros, string $columna): float
{
    $total = 0;
    foreach ($registros as $registro) {
        $total += $registro[$columna];
    }
    return $total;
}

/* EJEMPLO DE USO: sumarColumna

$actos = [
    ['lugar' => 'Teatro', 'aforo' => 100],
    ['lugar' => 'Auditorio', 'aforo' => 250]
];
$total = sumarColumna($actos, 'aforo');
echo $total; // 350
// Todos los registros deben tener la columna indicada con un número.
// sumarColumna([], 'aforo') devuelve 0.0.

FIN DEL EJEMPLO */

// Matrícula: horas HH:MM del MISMO día; no admite tramos de medianoche.
function duracionHoras(string $inicio, string $fin): float|false
{
    $patron = '/\A(?:[01][0-9]|2[0-3]):[0-5][0-9]\z/';
    if (!preg_match($patron, $inicio) || !preg_match($patron, $fin)) {
        return false;
    }
    $minInicio = (int) substr($inicio, 0, 2) * 60 + (int) substr($inicio, 3, 2);
    $minFin = (int) substr($fin, 0, 2) * 60 + (int) substr($fin, 3, 2);
    if ($minFin < $minInicio) {
        return false;
    }
    return ($minFin - $minInicio) / 60;
}

/* EJEMPLO DE USO: duracionHoras

$horas = duracionHoras('08:30', '10:00');
if ($horas === false) {
    exit('Tramo horario incorrecto.');
}
echo $horas; // 1.5 (una hora y media).
// duracionHoras('10:00', '08:30') devuelve false.
// duracionHoras('08:30', '08:30') devuelve 0.0: duración cero válida.

FIN DEL EJEMPLO */

// Test: devuelve siempre un array de CLAVES distintas, incluso para cantidad 1.
function elegirClavesAleatorias(array $datos, int $cantidad): array|false
{
    if ($cantidad < 1 || $cantidad > count($datos)) {
        return false;
    }
    return (array) array_rand($datos, $cantidad);
}

/* EJEMPLO DE USO: elegirClavesAleatorias

$banco = [
    'Capital de España' => ['Madrid', 'París'],
    '2 + 2' => ['3', '4'],
    'Color del cielo despejado' => ['Azul', 'Verde']
];
$seleccionadas = elegirClavesAleatorias($banco, 2);
if ($seleccionadas === false) {
    exit('Cantidad de preguntas incorrecta.');
}
foreach ($seleccionadas as $pregunta) {
    echo '<p>' . escapar($pregunta) . '</p>';
}
// Muestra 2 preguntas distintas; pueden cambiar en cada ejecución.
// Con cantidad 1 también devuelve un array. Con 4 devuelve false.

FIN DEL EJEMPLO */

// Misma estructura de preguntas/respuestas que Ej7 (índices vinculados).
// $opciones y $soluciones son los mapas de UNA asignatura del servidor.
// No uses validarSeleccion sobre preguntas: reindexa y rompería el vínculo.
function corregirTest($preguntas, $respuestas, array $opciones, array $soluciones): array|false
{
    if (!is_array($preguntas) || !is_array($respuestas) ||
        count($preguntas) < 1 || count($preguntas) > 5) {
        return false;
    }
    foreach ($respuestas as $indice => $respuesta) {
        if (!array_key_exists($indice, $preguntas)) {
            return false;
        }
    }
    $usadas = [];
    $aciertos = 0;
    $sinResponder = 0;
    foreach ($preguntas as $indice => $pregunta) {
        if (!is_string($pregunta) || !array_key_exists($pregunta, $opciones) ||
            !array_key_exists($pregunta, $soluciones) ||
            in_array($pregunta, $usadas, true)) {
            return false;
        }
        $usadas[] = $pregunta;
        $respuesta = array_key_exists($indice, $respuestas) ? $respuestas[$indice] : '';
        if (!is_string($respuesta)) {
            return false;
        }
        if ($respuesta === '') {
            $sinResponder++;
        } elseif (!in_array($respuesta, $opciones[$pregunta], true)) {
            return false;
        } elseif ($respuesta === $soluciones[$pregunta]) {
            $aciertos++;
        }
    }
    $total = count($preguntas);
    return [
        'total' => $total,
        'aciertos' => $aciertos,
        'fallos' => $total - $aciertos, // Incluye las preguntas en blanco.
        'sinResponder' => $sinResponder,
        'erroresContestados' => $total - $aciertos - $sinResponder,
        'nota' => round(10 * $aciertos / $total, 2)
    ];
}

/* EJEMPLO DE USO: corregirTest

// Datos del servidor para una asignatura:
$opciones = [
    '2 + 2' => ['3', '4'],
    'Capital de España' => ['Madrid', 'París'],
    '3 + 3' => ['5', '6']
];
$soluciones = [
    '2 + 2' => '4',
    'Capital de España' => 'Madrid',
    '3 + 3' => '6'
];
// Datos como los recibidos del formulario:
$preguntas = [0 => '2 + 2', 1 => 'Capital de España', 2 => '3 + 3'];
$respuestas = [0 => '4', 1 => 'París']; // La pregunta 2 queda sin responder.
$resultado = corregirTest($preguntas, $respuestas, $opciones, $soluciones);
if ($resultado === false) {
    exit('El test contiene datos incorrectos.');
}
echo $resultado['aciertos'];           // 1
echo $resultado['fallos'];             // 2 (incluye la pregunta en blanco).
echo $resultado['sinResponder'];       // 1
echo $resultado['erroresContestados']; // 1
echo $resultado['nota'];               // 3.33 sobre 10.
// Los índices vinculan cada pregunta con su respuesta: no los reordenes.
// Con POST, pasa $_POST['preguntas'] ?? null y $_POST['respuestas'] ?? [].

FIN DEL EJEMPLO */
