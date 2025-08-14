<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $codigo = trim($_POST['codigo']);
        $nombre = trim($_POST['nombre']);
        $duracion_semestres = (int)$_POST['duracion_semestres'];
        $departamento_id = (int)$_POST['departamento_id'];

        // Validaciones
        if (empty($codigo) || empty($nombre) || $duracion_semestres < 1 || $departamento_id < 1) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            exit;
        }

        // Verificar si el código ya existe (excluyendo el registro actual)
        $sql_check = "SELECT id FROM carreras WHERE codigo = ? AND id != ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$codigo, $id]);

        if ($stmt_check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El código de carrera ya existe']);
            exit;
        }

        // Actualizar carrera
        $sql = "UPDATE carreras SET 
                codigo = ?, 
                nombre = ?, 
                duracion_semestres = ?, 
                departamento_id = ? 
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$codigo, $nombre, $duracion_semestres, $departamento_id, $id]);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Carrera actualizada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la carrera']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
