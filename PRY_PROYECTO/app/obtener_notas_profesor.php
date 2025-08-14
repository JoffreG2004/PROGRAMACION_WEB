<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

// Verificar que se recibió el ID del profesor
if (!isset($_POST['profesor_id'])) {
    echo json_encode(['error' => 'ID del profesor no proporcionado']);
    exit;
}

$profesor_id = $_POST['profesor_id'];

try {
    // Obtener las notas de las materias que enseña el profesor
    $sql = "SELECT 
                n.id,
                CONCAT(e.nombre, ' ', e.apellido) as estudiante_nombre,
                e.ci as estudiante_ci,
                m.nrc,
                m.nombre as materia_nombre,
                n.n1,
                n.n2,
                n.n3,
                n.promedio,
                CASE 
                    WHEN n.promedio >= 14 THEN 'Aprobado'
                    ELSE 'Reprobado'
                END as estado
            FROM notas n
            INNER JOIN estudiantes e ON n.estudiante_id = e.id
            INNER JOIN materias m ON n.materia_id = m.id
            WHERE m.profesor_id = :profesor_id
            ORDER BY m.nombre, e.nombre, e.apellido";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':profesor_id', $profesor_id, PDO::PARAM_INT);
    $stmt->execute();
    $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notas);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
