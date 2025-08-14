<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        
        // Verificar si el profesor está asignado a alguna materia
        $materiaCheckSql = "SELECT COUNT(*) FROM materias WHERE profesor_id = ?";
        $materiaCheckStmt = $pdo->prepare($materiaCheckSql);
        $materiaCheckStmt->execute([$id]);

        if ($materiaCheckStmt->fetchColumn() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede eliminar el profesor porque está asignado a materias'
            ]);
            exit;
        }

        // Eliminar profesor
        $sql = "DELETE FROM profesor WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        echo json_encode([
            'success' => true,
            'message' => 'Profesor eliminado exitosamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar el profesor: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
