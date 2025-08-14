<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

// Verificar que sea una petición GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener el ID del estudiante
    $estudiante_id = trim($_GET['id'] ?? '');

    if (empty($estudiante_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de estudiante no proporcionado']);
        exit;
    }

    // Obtener los datos del estudiante
    $sql = "SELECT 
                e.id,
                e.ci,
                e.usuario,
                e.nombre,
                e.apellido,
                e.email,
                c.nombre as carrera_nombre,
                c.codigo as carrera_codigo,
                d.nombre as departamento_nombre
            FROM estudiantes e
            INNER JOIN carreras c ON e.carrera_id = c.id
            INNER JOIN departamentos d ON c.departamento_id = d.id
            WHERE e.id = :estudiante_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_STR);
    $stmt->execute();
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
        echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
        exit;
    }

    echo json_encode(['success' => true, 'estudiante' => $estudiante]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
}
