<?php
declare(strict_types=1);

class EquipoRepositorio
{
    public function __construct(private PDO $pdo)
    {
    }

    public function listarEquipos(): array
    {
        $equipos = $this->pdo
            ->query('SELECT id, nombre, unidades FROM equipo')
            ->fetchAll();

        $prestamos = $this->pdo
            ->query('SELECT equipo_id FROM prestamo WHERE devuelto = 0')
            ->fetchAll();

        $resultado = [];

        foreach ($equipos as $equipo) {
            $disponibles = $equipo['unidades'];

            foreach ($prestamos as $prestamo) {
                if ($equipo['id'] === $prestamo['equipo_id']) {
                    $disponibles--;
                }
            }

            $equipo['disponibles'] = $disponibles;
            $resultado[] = $equipo;
        }

        return $resultado;
    }
    public function listarPrestamos(): array
    {
        return $this->pdo
            ->query('SELECT p.id, e.nombre, p.persona, p.devuelto
                FROM equipo AS e
                INNER JOIN prestamo AS p ON e.id = p.equipo_id
                ORDER BY p.id DESC')
            ->fetchAll();
    }

    public function prestar(int $equipoId, string $persona): void
    {
        $persona = trim($persona);

        if ($equipoId < 1 || $persona === '') {
            throw new InvalidArgumentException('Id o persona no válido.');
        }

        $equipos = $this->listarEquipos();

        foreach ($equipos as $equipo) {

            if ($equipo['id'] === $equipoId) {

                if ($equipo['disponibles'] <= 0) {
                    throw new InvalidArgumentException(
                        'No quedan unidades disponibles.'
                    );
                }

                $sql = 'INSERT INTO prestamo(equipo_id, persona, devuelto)
                    VALUES (?, ?, ?)';
                $devuelto = 0;

                $sentencia = $this->pdo->prepare($sql);
                $sentencia->execute([$equipoId, $persona, $devuelto]);
                return;
            }
        }

        throw new InvalidArgumentException('El equipo no existe.');
    }

    public function devolver(int $prestamoId): bool
    {
        // Comprobamos que el prestamo exista y que no esté devuelto
        $sentencia = $this->pdo->prepare(
            'SELECT id, devuelto FROM prestamo WHERE id = ?'
        );

        $sentencia->execute([$prestamoId]);
        $prestamo = $sentencia->fetch();

        if ($prestamo === false) {
            throw new InvalidArgumentException('El préstamo no existe.');
        }

        if ($prestamo['devuelto'] === 1) {
            throw new InvalidArgumentException('El préstamo ya está devuelto.');
        }

        // Cambia el estado solamente si el préstamo sigue abierto.
        $sentencia = $this->pdo->prepare(
            'UPDATE prestamo SET devuelto = 1 WHERE id = ?'
        );
        $sentencia->execute([$prestamoId]);

        return true;
    }

    /*  public function devolver(int $prestamoId): bool
    {
        $sentencia = $this->pdo->prepare(
            'UPDATE prestamo SET devuelto = 1 WHERE id = ? AND devuelto = 0'
        );

        $sentencia->execute([$prestamoId]);

        return $sentencia->rowCount() > 0;
     } */
}
