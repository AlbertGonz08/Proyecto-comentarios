<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos los datos y quitamos espacios sobrantes
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');

    // Solo guardamos si los tres campos tienen contenido
    if ($titulo !== '' && $descripcion !== '' && $nombre_usuario !== '') {
        $stmt = $pdo->prepare("INSERT INTO comentarios (titulo, descripcion, nombre_usuario) VALUES (:titulo, :descripcion, :nombre_usuario)");
        $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':nombre_usuario' => $nombre_usuario
        ]);
    }
}

// Volvemos a la sección de comentarios
header("Location: index.php#comentarios");
exit;