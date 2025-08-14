<?php

require_once '../conexion/db.php';
// recibir datos por json
$request = json_decode(file_get_contents("php://input"), true);

$id = $request['id'];

// prepara mi query
$consulta = "SELECT usuario, password FROM administradores WHERE id = :id";
// ejecutar la consulta
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':id', $id);
$stmt->execute();
// obtener el resultado
$administrador = $stmt->fetch(PDO::FETCH_ASSOC);

// imprimir datos recibidos
echo json_encode($administrador);
