<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion/db.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener credenciales
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($usuario) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son requeridos']);
        exit;
    }

    // Buscar profesor por usuario
    $sql = "SELECT 
                p.id,
                p.nombre,
                p.apellido,
                p.usuario,
                p.contrasena,
                p.email,
                d.nombre as departamento_nombre
            FROM profesor p
            INNER JOIN departamentos d ON p.departamento_id = d.id
            WHERE p.usuario = :usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $profesor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profesor) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }

    // Verificar contraseña (asumiendo que está en texto plano, en producción debería usar password_verify)
    if ($password !== $profesor['contrasena']) {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        exit;
    }

    // Remover la contraseña de la respuesta por seguridad
    unset($profesor['contrasena']);

    echo json_encode([
        'success' => true,
        'message' => 'Acceso autorizado',
        'profesor' => $profesor
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
}
