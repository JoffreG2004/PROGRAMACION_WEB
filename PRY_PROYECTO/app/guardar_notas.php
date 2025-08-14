<?php
require_once '../conexion/db.php';

// Configurar la respuesta JSON
header('Content-Type: application/json');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

try {
    // Obtener datos del formulario
    $nota_id = trim($_POST['nota_id'] ?? ''); // Para edición
    $estudiante_id = trim($_POST['estudiante_id'] ?? '');
    $materia_id = trim($_POST['materia_id'] ?? '');
    $nota1 = trim($_POST['nota1'] ?? '');
    $nota2 = trim($_POST['nota2'] ?? '');
    $nota3 = trim($_POST['nota3'] ?? '');
    $promedio = trim($_POST['promedio'] ?? '');

    // Validar que todos los campos estén presentes
    if (empty($estudiante_id) || empty($materia_id) || empty($nota1) || empty($nota2) || empty($nota3)) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Validar que las notas estén en el rango correcto (0-10)
    $notas = [$nota1, $nota2, $nota3];
    foreach ($notas as $nota) {
        if ($nota < 0 || $nota > 20) {
            echo json_encode(['success' => false, 'error' => 'Las notas deben estar entre 0 y 20']);
            exit;
        }
    }

    // Verificar si es una edición (nota_id presente) o una nueva nota
    if (!empty($nota_id)) {
        // Editar nota existente por ID
        $sqlUpdate = "UPDATE notas SET n1 = :n1, n2 = :n2, n3 = :n3, promedio = :promedio 
                      WHERE id = :nota_id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':n1', $nota1);
        $stmtUpdate->bindParam(':n2', $nota2);
        $stmtUpdate->bindParam(':n3', $nota3);
        $stmtUpdate->bindParam(':promedio', $promedio);
        $stmtUpdate->bindParam(':nota_id', $nota_id);
        $stmtUpdate->execute();

        echo json_encode(['success' => true, 'message' => 'Nota actualizada exitosamente']);
    } else {
        // Verificar si ya existe una nota para este estudiante en esta materia
        $sqlCheck = "SELECT id FROM notas WHERE estudiante_id = :estudiante_id AND materia_id = :materia_id";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_STR);
        $stmtCheck->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            // Ya existe una nota para este estudiante en esta materia
            echo json_encode(['success' => false, 'error' => 'Ya existe una nota registrada para este estudiante en esta materia. Use la función de editar para modificar la nota existente.']);
            exit;
        } else {
            // Insertar nueva nota
            $sqlInsert = "INSERT INTO notas (estudiante_id, materia_id, n1, n2, n3, promedio) 
                          VALUES (:estudiante_id, :materia_id, :n1, :n2, :n3, :promedio)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_STR);
            $stmtInsert->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':n1', $nota1);
            $stmtInsert->bindParam(':n2', $nota2);
            $stmtInsert->bindParam(':n3', $nota3);
            $stmtInsert->bindParam(':promedio', $promedio);
            $stmtInsert->execute();

            echo json_encode(['success' => true, 'message' => 'Nota guardada exitosamente']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error general: ' . $e->getMessage()]);
}
