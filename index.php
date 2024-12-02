<?php
session_start();
require 'db.php';

// Registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = ($_POST['role'] === 'admin') ? 'admin' : 'user'; // Determinar rol

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        echo "Usuario registrado exitosamente!";
    } else {
        echo "Error al registrar usuario.";
    }
}

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirigir según el rol
        header("Location: " . ($user['role'] === 'admin' ? 'admin.php' : 'dashboard.php'));
        exit;
    } else {
        echo "Las credenciales de inicio de sesión no son válidas.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="header">
   <h1>Galería de imágenes</h1>
</div>
    <!-- Formulario de inicio de sesión -->
    <div class="container">
    <form method="POST">
        <h2>Iniciar sesión</h2>
        <input type="text" name="username" placeholder="nombre de usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="login">Accesar</button>
    </form>

    <!-- Formulario de registro -->
    <form method="POST">
        <h2>Registro</h2>
        <input type="text" name="username" placeholder="nombre de usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="role" required>
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit" name="register">Registrar</button>
    </form>
     <!-- Incluir archivo JavaScript -->
     <script src="/js/script.js"></script>
</body>
</html>
