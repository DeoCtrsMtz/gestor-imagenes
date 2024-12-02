<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administrador</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="header">
<header>
        <h1>Bienvenido administrador</h1>
        <a href="logout.php" class="logout-button">Cerrar sesión</a>
    </header>
    </div>

    <div class="container">
    <h1>Panel para administrar</h1>
    <h2>Usuario Administrador</h2>
    <ul>
        <?php
        $stmt = $pdo->query("SELECT * FROM users");
        while ($user = $stmt->fetch()) {
            echo '<li>' . $user['username'] . ' (' . $user['role'] . ') <a href="?delete_user=' . $user['id'] . '">Borrar</a></li>';
        }
        ?>
    </ul>
</body>
</html>
