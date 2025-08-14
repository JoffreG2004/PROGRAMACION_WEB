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
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM estudiantes WHERE usuario = :usuario");
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
    $id = trim($_POST['id'] ?? '');
    $ci = trim($_POST['ci'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $carrera_id = trim($_POST['carrera_id'] ?? '');

    // Validaciones básicas
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'El ID es requerido']);
        exit;
    }

    if (empty($ci)) {
        echo json_encode(['success' => false, 'message' => 'La CI es requerida']);
        exit;
    }

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
        exit;
    }

    if (empty($apellido)) {
        echo json_encode(['success' => false, 'message' => 'El apellido es requerido']);
        exit;
    }

    if (empty($carrera_id)) {
        echo json_encode(['success' => false, 'message' => 'La carrera es requerida']);
        exit;
    }

    // Verificar que la carrera existe
    $stmt_carrera = $pdo->prepare("SELECT id FROM carreras WHERE id = :carrera_id");
    $stmt_carrera->bindParam(':carrera_id', $carrera_id);
    $stmt_carrera->execute();

    if ($stmt_carrera->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'La carrera seleccionada no existe']);
        exit;
    }

    // Verificar que no exista otro estudiante con el mismo ID
    $stmt_check_id = $pdo->prepare("SELECT id FROM estudiantes WHERE id = :id");
    $stmt_check_id->bindParam(':id', $id);
    $stmt_check_id->execute();

    if ($stmt_check_id->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un estudiante con ese ID']);
        exit;
    }

    // Verificar que no exista otro estudiante con la misma CI
    $stmt_check_ci = $pdo->prepare("SELECT id FROM estudiantes WHERE ci = :ci");
    $stmt_check_ci->bindParam(':ci', $ci);
    $stmt_check_ci->execute();

    if ($stmt_check_ci->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un estudiante con esa CI']);
        exit;
    }

    // ... código anterior

    // Generar usuario, contraseña y email automáticamente
    $usuario = generarUsuario($nombre, $apellido, $pdo);
    $contrasena = generarContrasena();
    $email = $usuario . "@isem.edu.ec"; // Auto-generar email basado en usuario

    // Ya no encriptamos la contraseña, se guarda tal cual
    // $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // ... código para verificar email duplicado

    // Insertar el nuevo estudiante (guardamos la contraseña SIN encriptar)
    $sql = "INSERT INTO estudiantes (id, ci, usuario, contrasena, nombre, apellido, email, carrera_id) 
        VALUES (:id, :ci, :usuario, :contrasena, :nombre, :apellido, :email, :carrera_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':ci', $ci);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasena', $contrasena); // Aquí va la contraseña en texto plano
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':carrera_id', $carrera_id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Estudiante creado exitosamente',
            'estudiante' => [
                'id' => $id,
                'ci' => $ci,
                'usuario' => $usuario,
                'contrasena' => $contrasena, // En texto plano para mostrar luego
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'carrera_id' => $carrera_id
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el estudiante']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
