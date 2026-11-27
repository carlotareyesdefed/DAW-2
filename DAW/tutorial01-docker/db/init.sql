CREATE TABLE equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    unidades INT UNSIGNED NOT NULL
);

INSERT INTO
    equipo (nombre, unidades)
VALUES ('Portatil', 5),
    ('Monitor', 8),
    ('Teclado', 12);