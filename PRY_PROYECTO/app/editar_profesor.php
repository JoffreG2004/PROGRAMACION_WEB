<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $departamento_id = $_POST['departamento_id'];

        //si el profesor tiene una materia asignada, no se puede cambiar el departamento
        $sql_check = "SELECT COUNT(*) FROM materias WHERE profesor_id = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id]);
        $materias_count = $stmt_check->fetchColumn();
        if ($materias_count > 0 && $departamento_id != $_POST['departamento_actual']) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede cambiar el departamento de un profesor con materias asignadas'
            ]);
            exit;
        }
        


        function generarUsuario($nombre, $apellido, $pdo)
        {
            // Limpiar y normalizar nombre y apellido
            $nombre = strtolower(preg_replace('/[^a-zA-Z]/', '', $nombre));
            $apellido = strtolower(preg_replace('/[^a-zA-Z]/', '', $apellido));

            // Generar usuario base: primera letra del nombre + apellido
            $usuario_base = substr($nombre, 0, 1) . $apellido;
            $usuario = $usuario_base;
            $contador = 1;

            // Verificar si el usuario ya existe y generar uno único
            while (true) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM profesor WHERE usuario = :usuario");
                $stmt->bindParam(':usuario', $usuario);
                $stmt->execute();

                if ($stmt->fetchColumn() == 0) {
                    break; // Usuario disponible
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

        //generar usuario con los nuevos datos
        $usuario = generarUsuario($nombre, $apellido, $pdo);
        $contrasena = generarContrasena();
        


        // Actualizar profesor
        $sql = "UPDATE profesor SET nombre = ?, apellido = ?, departamento_id = ?, usuario = ?, contrasena = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $apellido, $departamento_id, $usuario, $contrasena, $id]);

        echo json_encode([
            'success' => true,
            'message' => 'Profesor actualizado exitosamente',
            'profesor' => [
                'usuario' => $usuario,
                'contraseña' => $contrasena
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el profesor: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
