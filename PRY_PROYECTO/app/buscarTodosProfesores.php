<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

try {
    $sql = "SELECT id, usuario, contrasena, nombre, apellido, email FROM profesor";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($profesores);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
