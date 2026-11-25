<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $comentario = trim($_POST['comentario'] ?? '');

    if (!empty($nombre) && !empty($comentario)) {
        $stmt = $pdo->prepare("INSERT INTO comentarios (nombre, comentario) VALUES (:nombre, :comentario)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':comentario' => $comentario
        ]);
    }
}

header("Location: index.php");
exit;
?>