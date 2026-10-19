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

function imprimirPie(): void
{
    echo '</body>';
    echo '</html>';
}

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function euros(float $numero): string {
    return number_format($numero, 2, ',', '.') . ' €';
}