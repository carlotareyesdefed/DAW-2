<?php
// función calcularMayoria($totalEscanos) que devuelva intdiv($totalEscanos, 2) + 1.
function calcularMayoria(int $totalEscanos): int
{
    return intdiv($totalEscanos, 2) + 1;
}
// función escapar($valor) para mostrar texto seguro en HTML.
function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
