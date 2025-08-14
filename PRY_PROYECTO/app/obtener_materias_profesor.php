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
    // Obtener las materias que enseña el profesor
    $sql = "SELECT m.id, m.nrc, m.nombre, m.creditos, d.nombre as departamento_nombre 
            FROM materias m 
            INNER JOIN departamentos d ON m.departamento_id = d.id 
            WHERE m.profesor_id = :profesor_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':profesor_id', $profesor_id, PDO::PARAM_INT);
    $stmt->execute();
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($materias);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
