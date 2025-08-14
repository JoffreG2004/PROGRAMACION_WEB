<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'];

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID del departamento es obligatorio']);
            exit;
        }

        // Verificar si el departamento tiene carreras asociadas
        $sql = "SELECT COUNT(*) as total FROM carreras WHERE departamento_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar el departamento porque tiene carreras asociadas']);
            exit;
        }

        // Verificar si el departamento tiene profesores asociados
        $sql = "SELECT COUNT(*) as total FROM profesor WHERE departamento_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar el departamento porque tiene profesores asociados']);
            exit;
        }

        // Eliminar el departamento
        $sql = "DELETE FROM departamentos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Departamento eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el departamento a eliminar']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
