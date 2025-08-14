<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener el ID del estudiante
    $estudiante_id = trim($_POST['estudiante_id'] ?? '');

    if (empty($estudiante_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de estudiante no proporcionado']);
        exit;
    }

    // Obtener las notas del estudiante con información de materias y profesores
    $sql = "SELECT 
                n.id,
                n.n1,
                n.n2,
                n.n3,
                n.promedio,
                CASE 
                    WHEN n.promedio >= 11 THEN 'Aprobado'
                    ELSE 'Reprobado'
                END as estado,
                m.nrc,
                m.nombre as materia_nombre,
                m.creditos,
                COALESCE(CONCAT(p.nombre, ' ', p.apellido), 'Sin asignar') as profesor_nombre,
                '' as profesor_apellido,
                d.nombre as departamento_nombre
            FROM notas n
            INNER JOIN materias m ON n.materia_id = m.id
            LEFT JOIN profesor p ON m.profesor_id = p.id
            INNER JOIN departamentos d ON m.departamento_id = d.id
            WHERE n.estudiante_id = :estudiante_id
            ORDER BY m.nombre ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_STR);
    $stmt->execute();
    $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'notas' => $notas]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
}
