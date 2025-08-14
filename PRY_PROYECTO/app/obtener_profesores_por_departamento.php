<?php
require_once '../conexion/db.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['departamento_id'])) {
    try {
        $departamento_id = (int)$_GET['departamento_id'];

        if ($departamento_id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de departamento inválido'
            ]);
            exit;
        }

        // Obtener profesores del departamento

        $sql = "SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo, email 
                FROM profesor 
                WHERE departamento_id = ? 
                ORDER BY apellido, nombre";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$departamento_id]);
        $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'profesores' => $profesores
        ]);
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
        'message' => 'Parámetros incorrectos'
    ]);
}
