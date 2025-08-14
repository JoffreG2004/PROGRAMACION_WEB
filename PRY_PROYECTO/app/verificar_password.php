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
    $tipo = $_POST['tipo'] ?? ''; // 'estudiante', 'profesor', 'admin'
    $id = $_POST['id'] ?? '';
    $contrasena_ingresada = $_POST['contrasena'] ?? '';

    if (empty($tipo) || empty($id) || empty($contrasena_ingresada)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    $tabla = '';
    $campo_id = 'id';
    $campo_password = 'contrasena';

    switch ($tipo) {
        case 'estudiante':
            $tabla = 'estudiantes';
            break;
        case 'profesor':
            $tabla = 'profesor';
            break;
        case 'admin':
            $tabla = 'administradores';
            $campo_password = 'password';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Tipo no válido']);
            exit;
    }

    // Obtener el hash de la base de datos
    $stmt = $pdo->prepare("SELECT $campo_password FROM $tabla WHERE $campo_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }

    $hash_bd = $resultado[$campo_password];

    // Verificar la contraseña
    if (password_verify($contrasena_ingresada, $hash_bd)) {
        echo json_encode([
            'success' => true,
            'message' => 'Contraseña correcta',
            'hash' => $hash_bd
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
