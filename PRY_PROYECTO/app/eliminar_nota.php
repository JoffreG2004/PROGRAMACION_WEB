<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

// Verificar que sea una petición GET (para DELETE desde JavaScript)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener el ID de la nota a eliminar
    $nota_id = trim($_GET['id'] ?? '');

    if (empty($nota_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de nota no proporcionado']);
        exit;
    }

    // Verificar que la nota existe
    $sqlCheck = "SELECT id FROM notas WHERE id = :nota_id";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':nota_id', $nota_id, PDO::PARAM_INT);
    $stmtCheck->execute();

    if ($stmtCheck->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Nota no encontrada']);
        exit;
    }

    // Eliminar la nota
    $sqlDelete = "DELETE FROM notas WHERE id = :nota_id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->bindParam(':nota_id', $nota_id, PDO::PARAM_INT);
    $stmtDelete->execute();

    echo json_encode(['success' => true, 'message' => 'Nota eliminada exitosamente']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
}
