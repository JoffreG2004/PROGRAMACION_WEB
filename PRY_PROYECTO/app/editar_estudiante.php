<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $ci = $_POST['ci'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $carrera_id = $_POST['carrera_id'];

        // Validar que la carrera exista
        $stmt_carrera = $pdo->prepare("SELECT id FROM carreras WHERE id = ?");
        $stmt_carrera->execute([$carrera_id]);
        if ($stmt_carrera->rowCount() === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'La carrera seleccionada no existe'
            ]);
            exit;
        }

        // Función para generar usuario único
        function generarUsuario($nombre, $apellido, $pdo)
        {
            $nombre = strtolower(preg_replace('/[^a-zA-Z]/', '', $nombre));
            $apellido = strtolower(preg_replace('/[^a-zA-Z]/', '', $apellido));

            $usuario_base = substr($nombre, 0, 1) . $apellido;
            $usuario = $usuario_base;
            $contador = 1;

            while (true) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM estudiantes WHERE usuario = :usuario");
                $stmt->bindParam(':usuario', $usuario);
                $stmt->execute();

                if ($stmt->fetchColumn() == 0) {
                    break;
                }

                $usuario = $usuario_base . $contador;
                $contador++;
            }

            return $usuario;
        }

        // Función para generar contraseña de 6 dígitos
        function generarContrasena()
        {
            return str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        }

        // Generar usuario y contraseña nuevos
        $usuario = generarUsuario($nombre, $apellido, $pdo);
        $contrasena = generarContrasena();

        // Actualizar estudiante incluyendo usuario y contraseña
        $sql = "UPDATE estudiantes SET ci = ?, nombre = ?, apellido = ?, carrera_id = ?, usuario = ?, contrasena = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ci, $nombre, $apellido, $carrera_id, $usuario, $contrasena, $id]);

        echo json_encode([
            'success' => true,
            'message' => 'Estudiante actualizado exitosamente',
            'estudiante' => [
                'usuario' => $usuario,
                'contraseña' => $contrasena
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el estudiante: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
