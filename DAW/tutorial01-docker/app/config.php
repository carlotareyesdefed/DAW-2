<?php
$pdo = new PDO(
'mysql:host=db;port=3306;dbname=catalogo;charset=utf8mb4',
'alumno',
'alumno123',
[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);