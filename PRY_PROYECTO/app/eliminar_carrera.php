<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)$input['id'];

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de carrera inválido']);
            exit;
        }

        // Verificar si la carrera tiene estudiantes asociados
        $sql_check_students = "SELECT COUNT(*) as count FROM estudiantes WHERE carrera_id = ?";
        $stmt_check_students = $pdo->prepare($sql_check_students);
        $stmt_check_students->execute([$id]);
        $student_count = $stmt_check_students->fetch(PDO::FETCH_ASSOC)['count'];

        if ($student_count > 0) {
            echo json_encode(['success' => false, 'message' => "No se puede eliminar la carrera porque tiene $student_count estudiante(s) asociado(s)"]);
            exit;
        }

        // Verificar si la carrera tiene materias asociadas (las materias ahora dependen de departamentos, no carreras)
        // Esta verificación ya no es necesaria porque las materias ahora están asociadas a departamentos
        // Eliminar carrera
        $sql = "DELETE FROM carreras WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$id]);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Carrera eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la carrera']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
