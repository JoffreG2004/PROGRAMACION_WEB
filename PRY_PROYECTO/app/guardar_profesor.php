<?php
require_once '../conexion/db.php';

// Configurar la respuesta JSON
header('Content-Type: application/json');

// Función para generar usuario único
function generarUsuario($nombre, $apellido, $pdo)
{
    // Limpiar y normalizar nombre y apellido
    $nombre = strtolower(preg_replace('/[^a-zA-Z]/', '', $nombre));
    $apellido = strtolower(preg_replace('/[^a-zA-Z]/', '', $apellido));

    // Generar usuario base: primera letra del nombre + apellido
    $usuario_base = substr($nombre, 0, 1) . $apellido;
    $usuario = $usuario_base;
    $contador = 1;

    // Verificar si el usuario ya existe y generar uno único
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM profesor WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {
            break; // Usuario disponible
        }

        $usuario = $usuario_base . $contador;
        $contador++;
    }

    return $usuario;
}

// Función para generar contraseña de 6 dígitos
function generarContrasena()
{
    return str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
}

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Obtener y validar los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $departamento_id = trim($_POST['departamento_id'] ?? '');

    // Validaciones básicas
    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
        exit;
    }

    if (empty($apellido)) {
        echo json_encode(['success' => false, 'message' => 'El apellido es requerido']);
        exit;
    }

    if (empty($departamento_id)) {
        echo json_encode(['success' => false, 'message' => 'El departamento es requerido']);
        exit;
    }

    // Verificar que el departamento existe
    $stmt_dept = $pdo->prepare("SELECT id FROM departamentos WHERE id = :departamento_id");
    $stmt_dept->bindParam(':departamento_id', $departamento_id);
    $stmt_dept->execute();

    if ($stmt_dept->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'El departamento seleccionado no existe']);
        exit;
    }

    // Generar usuario, contraseña y email automáticamente
    $usuario = generarUsuario($nombre, $apellido, $pdo);
    $contrasena = generarContrasena();
    $email = $usuario . "@isem.edu.ec"; // Auto-generar email basado en usuario

    // Verificar que no exista otro profesor con el email generado (por si acaso)
    $stmt_check_email = $pdo->prepare("SELECT id FROM profesor WHERE email = :email");
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Error: el email generado ya existe']);
        exit;
    }

    // Insertar el nuevo profesor SIN encriptar la contraseña
    $sql = "INSERT INTO profesor (nombre, apellido, email, usuario, contrasena, departamento_id) VALUES (:nombre, :apellido, :email, :usuario, :contrasena, :departamento_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasena', $contrasena); // Aquí pasa la contraseña sin encriptar
    $stmt->bindParam(':departamento_id', $departamento_id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Profesor creado exitosamente',
            'profesor' => [
                'id' => $pdo->lastInsertId(),
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'usuario' => $usuario,
                'contrasena' => $contrasena, // Contraseña sin encriptar para mostrar una vez
                'departamento_id' => $departamento_id
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el profesor']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
