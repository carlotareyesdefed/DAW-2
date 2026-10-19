<?php
function euros(float $numero): string {
    return number_format($numero, 2, ',', '.') . ' €';
}
function escapar(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
