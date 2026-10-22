<?php

// Comprueba que el formulario se ha enviado con el método indicado (GET o POST).
function exigirMetodo(string $metodo): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($metodo)) {
        http_response_code(405);
        exit('Método de envío incorrecto. Accede desde el formulario.');
    }
}

// Valida que un campo recibido por POST sea un entero dentro de un rango.
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

// Valida que un campo recibido por POST sea un decimal dentro de un rango.
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

// Valida directamente un valor entero, sin obtenerlo de $_POST.
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

// Valida directamente un valor decimal, con mínimo obligatorio y máximo opcional.
function validarValorDecimal($valor, float $min, ?float $max = null): float|false
{
    if (!is_string($valor) && !is_int($valor) && !is_float($valor)) {
        return false;
    }

    $numero = filter_var($valor, FILTER_VALIDATE_FLOAT);

    if (
        $numero === false || $numero < $min ||
        ($max !== null && $numero > $max)
    ) {
        return false;
    }

    return $numero;
}

// Comprueba que un valor de radio/select sea una de las opciones permitidas.
function validarOpcionPermitida($valor, array $permitidas): string|false
{
    return is_string($valor) && in_array($valor, $permitidas, true)
        ? $valor : false;
}

// Valida un texto, elimina espacios al principio y final y comprueba su longitud.
function validarTexto($valor, int $min = 1, int $max = 80): string|false
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

// Comprueba que un valor exista como CLAVE de un array asociativo.
function validarClaveCatalogo($valor, array $catalogo): string|false
{
    return is_string($valor) && array_key_exists($valor, $catalogo)
        ? $valor : false;
}

// Devuelve todas las claves que tienen el valor máximo de un array (incluidos empates).
function obtenerClavesMaximo(array $datos): array
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

// Selecciona aleatoriamente una cantidad de claves distintas de un array.
function elegirClavesAleatorias(array $datos, int $cantidad): array|false
{
    if ($cantidad < 1 || $cantidad > count($datos)) {
        return false;
    }

    return (array) array_rand($datos, $cantidad);
}

// Convierte caracteres especiales en entidades HTML para mostrar texto de forma segura.
function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

// Formatea un número como importe en euros (ejemplo: 1234.5 -> 1.234,50 €).
function euros(float $numero): string
{
    return number_format($numero, 2, ',', '.') . ' €';
}

function calcularPrioridad(int $valor): string
{
    if ($valor <= 2) {
        return 'BAJA';
    } elseif ($valor <= 5) {
        return 'MEDIA';
    }

    return 'ALTA';
}

//Valida que las claves de un array coincidan con las claves de otro array
function validarClavesArray($claves, array $catalogo): bool
{
    if (!is_array($claves)) {
        return false;
    }

    foreach ($claves as $clave) {
        if (
            !is_string($clave) ||
            !array_key_exists($clave, $catalogo)
        ) {
            return false;
        }
    }

    return true;
}


// array_diff
$clavesPistas = [
    'gps',
    'camara',
    'rumor',
    'testigo'
];

$decisiones = [
    'gps' => 'Investigar',
    'rumor' => 'Descartar'
];

$sinResponder = array_diff(
    $clavesPistas,
    array_keys($decisiones)
);

print_r($sinResponder);

// Array
// (
//     [1] => camara
//     [3] => testigo
// )

// array_diff_key

$pistas = [
    'gps' => ['fiable' => true],
    'camara' => ['fiable' => true],
    'rumor' => ['fiable' => false]
];

$decisiones = [
    'gps' => 'Investigar',
    'rumor' => 'Descartar'
];

$sinResponder = array_diff_key($pistas, $decisiones);

print_r($sinResponder);

// Array
// (
//     [camara] => Array
//         (
//             [fiable] => 1
//         )
// )