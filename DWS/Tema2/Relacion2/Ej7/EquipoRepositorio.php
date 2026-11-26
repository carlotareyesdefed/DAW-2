<?php
declare(strict_types=1);

class EquipoRepositorio
{
    public function __construct(private PDO $pdo)
    {
    }

    public function listar(): array
    {
        // Obtener todos los equipos de la base de datos ordenados alfabeticamente
        return $this->pdo
            ->query('SELECT * FROM equipo ORDER BY nombre')
            ->fetchAll();
    }

    public function crear(string $nombre, int $unidades): void
    {
        $nombre = trim($nombre);
        if (
            !filter_var($unidades, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]])
            || $nombre === '' || strlen($nombre) < 2 || strlen($nombre) > 80
        ) {
            throw new InvalidArgumentException('Datos incorrectos.');
        }

        $sql = 'INSERT INTO equipo(nombre, unidades) VALUES (?, ?)';
        $sentencia = $this->pdo->prepare($sql);
        $sentencia->execute([$nombre, $unidades]);
    }

    public function actualizarUnidades(int $id, int $unidades): void
    {
        if ($id < 1 || !filter_var($unidades, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('Id o unidades inválidas.');
        }
        $sentencia = $this->pdo->prepare('UPDATE equipo SET unidades = ? WHERE id = ?');
        $sentencia->execute([$unidades, $id]);
    }

    public function eliminar(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('Id no válido.');
        }
        $sentencia = $this->pdo->prepare('DELETE FROM equipo WHERE id = ?');
        $sentencia->execute([$id]);
    }
}