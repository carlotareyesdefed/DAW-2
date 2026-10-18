<?php
// TODO: crea una función euros($numero) para mostrar cantidades con 2 decimales.
function euros(float $numero): string {
    return number_format($numero, 2, ',', '.') . ' €';
}
// TODO: crea una función escapar($texto) que use htmlspecialchars.
function escapar(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
