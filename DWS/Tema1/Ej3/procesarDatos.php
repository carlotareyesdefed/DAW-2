<?php
// EJERCICIO 03. Los datos llegan desde radioCheckbox.html por POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];
    $sexo = $_POST['sexo'];
    $estadoCivil = $_POST['estado-civil'];
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

    if (mb_strlen($nombre, "UTF-8") <= 20) {
        if (mb_strlen($apellidos, "UTF-8") <= 20) {
            echo "<h1>" . htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") . " " . htmlspecialchars($apellidos, ENT_QUOTES, "UTF-8") . "</h1>";
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

    $peso_validado = filter_var($peso, FILTER_VALIDATE_FLOAT);

    if ($peso_validado === false || $peso_validado < 20 || $peso_validado > 500) {
        echo "<p>Error: El peso debe ser un número válido entre 20 y 500 kilos.</p>";
    } else {
        echo "<p>" . $peso_validado . " kg</p>";
    }

    if (in_array($sexo, $genero)) {
        echo "<p>" . htmlspecialchars($sexo, ENT_QUOTES, "UTF-8") . "</p>";
    } else {
        echo "<p>El sexo introducido no es válido</p>";
    }

    if (in_array($estadoCivil, $estadosCiviles)) {
        echo "<p>" . htmlspecialchars($estadoCivil, ENT_QUOTES, "UTF-8") . "</p>";
    } else {
        echo "<p>El estado civil introducido no es válido</p>";
    }

    if (empty($aficiones)) {
        echo "<p>No has seleccionado ninguna afición.</p>";
    } else {
        echo "<ul>";
        foreach ($aficiones as $aficion) {
            echo "<li>" . htmlspecialchars($aficion, ENT_QUOTES, "UTF-8") . "</li>";
        }
        echo "</ul>";
    }
}


