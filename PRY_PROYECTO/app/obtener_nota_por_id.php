<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

// Verificar que sea una petición GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener el ID de la nota a editar
    $nota_id = trim($_GET['id'] ?? '');

    if (empty($nota_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de nota no proporcionado']);
        exit;
    }

    // Obtener los datos de la nota
    $sql = "SELECT 
                n.id,
                n.estudiante_id,
                n.materia_id,
                n.n1,
                n.n2,
                n.n3,
                n.promedio,
                CONCAT(e.nombre, ' ', e.apellido) as estudiante_nombre,
                e.ci as estudiante_ci,
                m.nrc,
                m.nombre as materia_nombre
            FROM notas n
            INNER JOIN estudiantes e ON n.estudiante_id = e.id
            INNER JOIN materias m ON n.materia_id = m.id
            WHERE n.id = :nota_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nota_id', $nota_id, PDO::PARAM_INT);
    $stmt->execute();
    $nota = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$nota) {
        echo json_encode(['success' => false, 'message' => 'Nota no encontrada']);
        exit;
    }

    echo json_encode(['success' => true, 'nota' => $nota]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
}
