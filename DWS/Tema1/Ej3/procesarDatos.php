<?php
// EJERCICIO 03. Los datos llegan desde radioCheckbox.html por POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $apellidos = trim((string) ($_POST['apellidos' ?? '']));
    $edad = (string) ($_POST['edad'] ?? '');
    $peso = filter_var($_POST['peso'] ?? null, FILTER_VALIDATE_FLOAT);
    $sexo = (string) ($_POST['sexo'] ?? '');
    $estadoCivil = (string) ($_POST['estado-civil'] ?? '');
    $aficiones = $_POST['aficiones'] ?? [];

    $edadesValidas = [
        "Menos de 20 años",
        "Entre 20 y 39 años",
        "Entre 40 y 59 años",
        "60 años o más"
    ];
    $genero = [
        "femenino",
        "masculino"
    ];
    $estadosCiviles = [
        "soltero",
        "casado",
        "otro"
    ];

    $aficionesValidas = [
        'cine',
        'literatura',
        'tebeos',
        'deporte',
        'musica',
        'television'
    ];


    if (mb_strlen($nombre, "UTF-8") <= 20) {
        if (mb_strlen($apellidos, "UTF-8") <= 20) {
            echo "<h1>" . htmlspecialchars(ucfirst($nombre), ENT_QUOTES, "UTF-8") . " " . htmlspecialchars(ucwords($apellidos), ENT_QUOTES, "UTF-8") . "</h1>";
        } else {
            echo "El apellido no puede tener más de 20 caracteres";
        }
    } else {
        echo "El nombre no puede tener más de 20 caracteres";
    }

    if (in_array($edad, $edadesValidas)) {
        echo "<p>" . htmlspecialchars($edad, ENT_QUOTES, "UTF-8") . "</p>";
    } else {
        echo "<p>La edad no es válida</p>";
    }

    if ($peso === false || $peso < 20 || $peso > 500) {
        echo "<p>Error: El peso debe ser un número válido entre 20 y 500 kilos.</p>";
    } else {
        echo "<p>" . $peso . " kg</p>";
    }

    if (in_array($sexo, $genero)) {
        echo "<p>" . htmlspecialchars(ucfirst($sexo), ENT_QUOTES, "UTF-8") . "</p>";
    } else {
        echo "<p>El sexo introducido no es válido</p>";
    }

    if (in_array($estadoCivil, $estadosCiviles)) {
        echo "<p>" . htmlspecialchars($estadoCivil, ENT_QUOTES, "UTF-8") . "</p>";
    } else {
        echo "<p>El estado civil introducido no es válido</p>";
    }

    foreach ($aficiones as $aficion) {
        if (
            !is_string($aficion) ||
            !in_array($aficion, $aficionesValidas, true)
        ) {
            exit('Se ha recibido una afición no válida.');
        }
    }
}




