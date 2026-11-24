<?php
// Datos por defecto (Laragon y XAMPP usan root sin contraseña)
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "comentarios_db";

// Si existe config.local.php, usamos esos datos (para quien tenga contraseña)
if (file_exists(__DIR__ . "/config.local.php")) {
    include __DIR__ . "/config.local.php";
}


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Nos conectamos al servidor sin elegir base de datos todavía
    $conn = new mysqli($host, $usuario, $clave);
    $conn->set_charset("utf8mb4");

    
    $conn->query("CREATE DATABASE IF NOT EXISTS `$bd` CHARACTER SET utf8mb4");
    $conn->select_db($bd);

    
    $conn->query("CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(150) NOT NULL,
        descripcion TEXT NOT NULL,
        nombre_usuario VARCHAR(50) NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (mysqli_sql_exception $e) {
    die("No se pudo conectar a MySQL. Verifica que esté iniciado y que el usuario y la contraseña sean correctos. Detalle: " . $e->getMessage());
}