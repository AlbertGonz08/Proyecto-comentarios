<?php
// Datos por defecto (Laragon y XAMPP usan root sin contraseña)
$host = "localhost";
$dbname = "comentarios_db";
$user = "root";
$pass = "";

// En Replit (plantilla PHP + MariaDB) los datos vienen de los Secrets
if (getenv('db_user')) {
    $host = "127.0.0.1";
    $user = getenv('db_user');
    $pass = getenv('db_pass') ?: "";
    $dbname = getenv('db_name') ?: $dbname;
}

// Si hay variables DB_* (otro hosting de MySQL), tienen prioridad
$host = getenv('DB_HOST') ?: $host;
$user = getenv('DB_USER') ?: $user;
$pass = getenv('DB_PASS') ?: $pass;
$dbname = getenv('DB_NAME') ?: $dbname;

// Si existe config.local.php, usamos su contraseña (solo en tu PC)
if (file_exists(__DIR__ . "/config.local.php")) {
    include __DIR__ . "/config.local.php";
    $pass = $clave ?? $pass;
}

try {
    // Nos conectamos al servidor sin elegir base de datos todavía
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creamos la base de datos si no existe y la seleccionamos
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4");
    $pdo->exec("USE `$dbname`");

    // Creamos la tabla si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(150) NOT NULL,
        descripcion TEXT NOT NULL,
        nombre_usuario VARCHAR(50) NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}