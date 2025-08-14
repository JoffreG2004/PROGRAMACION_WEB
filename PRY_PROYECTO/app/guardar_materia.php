<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nrc = trim($_POST['nrc']);
        $nombre = trim($_POST['nombre']);
        $creditos = (int)$_POST['creditos'];
        $departamento_id = (int)$_POST['departamento_id'];
        $profesor_id = !empty($_POST['profesor_id']) ? (int)$_POST['profesor_id'] : null;

        // Validaciones básicas
        if (empty($nrc) || empty($nombre) || $creditos <= 0 || $departamento_id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Todos los campos obligatorios deben estar completos'
            ]);
            exit;
        }

        // Verificar que el NRC no exista
        $sql_check = "SELECT id FROM materias WHERE nrc = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$nrc]);

        if ($stmt_check->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'El NRC ya existe'
            ]);
            exit;
        }

        // Verificar que el departamento existe
        $sql_dept = "SELECT id FROM departamentos WHERE id = ?";
        $stmt_dept = $pdo->prepare($sql_dept);
        $stmt_dept->execute([$departamento_id]);

        if ($stmt_dept->rowCount() === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'El departamento seleccionado no existe'
            ]);
            exit;
        }

        // Si se seleccionó un profesor, verificar que pertenezca al departamento
        if ($profesor_id) {
            $sql_prof = "SELECT id FROM profesor WHERE id = ? AND departamento_id = ?";
            $stmt_prof = $pdo->prepare($sql_prof);
            $stmt_prof->execute([$profesor_id, $departamento_id]);

            if ($stmt_prof->rowCount() === 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El profesor seleccionado no pertenece al departamento'
                ]);
                exit;
            }
        }

        // Insertar la materia
        $sql = "INSERT INTO materias (nrc, nombre, creditos, departamento_id, profesor_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$nrc, $nombre, $creditos, $departamento_id, $profesor_id]);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Materia creada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la materia'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
