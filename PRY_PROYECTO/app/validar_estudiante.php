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

    // Buscar estudiante por usuario
    $sql = "SELECT 
                e.id,
                e.ci,
                e.usuario,
                e.contrasena,
                e.nombre,
                e.apellido,
                e.email,
                c.nombre as carrera_nombre,
                c.codigo as carrera_codigo,
                d.nombre as departamento_nombre
            FROM estudiantes e
            INNER JOIN carreras c ON e.carrera_id = c.id
            INNER JOIN departamentos d ON c.departamento_id = d.id
            WHERE e.usuario = :usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }

    // Verificar contraseña (asumiendo que está en texto plano, en producción debería usar password_verify)
    if ($password !== $estudiante['contrasena']) {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        exit;
    }

    // Remover la contraseña de la respuesta por seguridad
    unset($estudiante['contrasena']);

    echo json_encode([
        'success' => true,
        'message' => 'Acceso autorizado',
        'estudiante' => $estudiante
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
}
