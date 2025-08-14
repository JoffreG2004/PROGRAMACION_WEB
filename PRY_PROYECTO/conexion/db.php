<?php

// conexion/db.php - SISTEMA DE GESTIÓN ACADÉMICA
$host = 'localhost';
$dbname = 'crud_proyecto';  // ← Tu nueva base de datos
$username = 'crud_proyecto';  // ← Tu nuevo proyecto
$password = '12345';  // ← La contraseña que pusiste
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    // la variable $pdo se utiliza para crear una conexión a la base de datos
    // PDO es una extensión de PHP que permite acceder a bases de datos de manera segura y eficiente
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa a la base de datos del proyecto académico."; // Comentado para no interferir con JSON
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
