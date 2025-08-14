<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);

        // Validaciones
        if (empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'El nombre del departamento es obligatorio']);
            exit;
        }

        // Verificar si ya existe otro departamento con el mismo nombre
        $sql = "SELECT id FROM departamentos WHERE nombre = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un departamento con ese nombre']);
            exit;
        }

        // Actualizar el departamento
        $sql = "UPDATE departamentos SET nombre = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $id]);

        echo json_encode(['success' => true, 'message' => 'Departamento actualizado correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
