<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

try {
    // Obtener todos los estudiantes
    $sql = "SELECT e.id, e.ci, e.nombre, e.apellido, e.email, c.nombre as carrera_nombre 
            FROM estudiantes e 
            INNER JOIN carreras c ON e.carrera_id = c.id 
            ORDER BY e.nombre, e.apellido";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($estudiantes);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
