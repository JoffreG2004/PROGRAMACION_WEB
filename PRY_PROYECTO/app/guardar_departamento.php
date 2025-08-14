<?php
require_once '../conexion/db.php';

// Configurar la respuesta JSON
header('Content-Type: application/json');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener y validar los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');

    // Validaciones básicas
    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del departamento es requerido']);
        exit;
    }

    // Verificar que no exista otro departamento con el mismo nombre
    $stmt_check = $pdo->prepare("SELECT id FROM departamentos WHERE nombre = :nombre");
    $stmt_check->bindParam(':nombre', $nombre);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un departamento con ese nombre']);
        exit;
    }

    // Insertar el nuevo departamento
    $sql = "INSERT INTO departamentos (nombre) VALUES (:nombre)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Departamento creado exitosamente',
            'departamento' => [
                'id' => $pdo->lastInsertId(),
                'nombre' => $nombre
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el departamento']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
