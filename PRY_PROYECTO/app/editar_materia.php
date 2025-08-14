<?php
require_once '../conexion/db.php';


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $nrc = trim($_POST['nrc']);
        $nombre = trim($_POST['nombre']);
        $creditos = (int)$_POST['creditos'];
        $departamento_id = (int)$_POST['departamento_id'];
        $profesor_id = !empty($_POST['profesor_id']) ? (int)$_POST['profesor_id'] : null;


        // Validaciones
        if (empty($nrc) || empty($nombre) || $creditos < 1 || $departamento_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben estar completos']);
            exit;
        }

        // Verificar si el NRC ya existe (excluyendo el registro actual)
        $sql_check = "SELECT id FROM materias WHERE nrc = ? AND id != ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$nrc, $id]);

        if ($stmt_check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El NRC ya existe']);
            exit;
        }

        // Verificar que el departamento existe
        $sql_dept = "SELECT id FROM departamentos WHERE id = ?";
        $stmt_dept = $pdo->prepare($sql_dept);
        $stmt_dept->execute([$departamento_id]);

        if ($stmt_dept->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'El departamento seleccionado no existe']);
            exit;
        }

        // Si se seleccionó un profesor, verificar que pertenezca al departamento
        if ($profesor_id) {
            $sql_prof = "SELECT id FROM profesor WHERE id = ? AND departamento_id = ?";
            $stmt_prof = $pdo->prepare($sql_prof);
            $stmt_prof->execute([$profesor_id, $departamento_id]);

            if ($stmt_prof->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'El profesor seleccionado no pertenece al departamento']);
                exit;
            }
        }

        // Actualizar materia
        $sql = "UPDATE materias SET 
                nrc = ?, 
                nombre = ?, 
                creditos = ?,
                departamento_id = ?,
                profesor_id = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$nrc, $nombre, $creditos, $departamento_id, $profesor_id, $id]);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Materia actualizada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la materia']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
