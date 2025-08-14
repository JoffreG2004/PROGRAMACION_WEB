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
    $codigo = trim($_POST['codigo'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $duracion = trim($_POST['duracion'] ?? '');
    $departamento_id = trim($_POST['departamento'] ?? '');

    // Validaciones básicas
    if (empty($codigo)) {
        echo json_encode(['success' => false, 'message' => 'El código es requerido']);
        exit;
    }

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
        exit;
    }

    if (empty($duracion)) {
        echo json_encode(['success' => false, 'message' => 'La duración es requerida']);
        exit;
    }

    if (empty($departamento_id)) {
        echo json_encode(['success' => false, 'message' => 'El departamento es requerido']);
        exit;
    }

    // Verificar que el departamento existe
    $stmt_depto = $pdo->prepare("SELECT id FROM departamentos WHERE id = :departamento_id");
    $stmt_depto->bindParam(':departamento_id', $departamento_id);
    $stmt_depto->execute();
    
    if ($stmt_depto->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'El departamento seleccionado no existe']);
        exit;
    }

    // Verificar que no exista otra carrera con el mismo código
    $stmt_check_codigo = $pdo->prepare("SELECT id FROM carreras WHERE codigo = :codigo");
    $stmt_check_codigo->bindParam(':codigo', $codigo);
    $stmt_check_codigo->execute();
    
    if ($stmt_check_codigo->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una carrera con ese código']);
        exit;
    }

    // Verificar que no exista otra carrera con el mismo nombre
    $stmt_check_nombre = $pdo->prepare("SELECT id FROM carreras WHERE nombre = :nombre");
    $stmt_check_nombre->bindParam(':nombre', $nombre);
    $stmt_check_nombre->execute();
    
    if ($stmt_check_nombre->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una carrera con ese nombre']);
        exit;
    }

    // Insertar la nueva carrera
    $sql = "INSERT INTO carreras (nombre, codigo, duracion_semestres, departamento_id) VALUES (:nombre, :codigo, :duracion, :departamento_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':duracion', $duracion);
    $stmt->bindParam(':departamento_id', $departamento_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Carrera creada exitosamente',
            'carrera' => [
                'id' => $pdo->lastInsertId(),
                'codigo' => $codigo,
                'nombre' => $nombre,
                'duracion' => $duracion,
                'departamento_id' => $departamento_id
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear la carrera']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
