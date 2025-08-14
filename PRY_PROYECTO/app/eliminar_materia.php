<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)$input['id'];

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de materia inválido']);
            exit;
        }

        // Eliminar materia
        $sql = "DELETE FROM materias WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$id]);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Materia eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la materia']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
