<?php
session_start();
require 'db.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Obtener el ID del usuario actual
$user_id = $_SESSION['user_id'];

// Manejar la subida de imágenes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $filename = $_FILES['image']['name'];
    $filepath = 'uploads/' . basename($filename);

    // Verificar que sea una imagen válida
    $file_type = mime_content_type($_FILES['image']['tmp_name']);
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
            // Guardar el nombre de la imagen en la base de datos
            $stmt = $pdo->prepare("INSERT INTO images (user_id, filename) VALUES (?, ?)");
            $stmt->execute([$user_id, $filename]);
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "El archivo debe ser una imagen (JPEG, PNG o GIF).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <<div class="header">
        <header>
        <h1>Bienvenido a tu galería</h1>
        <a href="logout.php" class="logout-button">Cerrar sesión</a>
    </header>
</div>

    <section>
        <h2>Sube una nueva imagen</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit">Subir imagen</button>
        </form>
    </section>
    <div class="container">
    <section>
        <h2>Tu galería</h2>
        <div class="gallery">
            <?php
            // Obtener las imágenes del usuario actual
            $stmt = $pdo->prepare("SELECT * FROM images WHERE user_id = ?");
            $stmt->execute([$user_id]);

            while ($image = $stmt->fetch()) {
                echo '<div class="gallery-item">';
                echo '<img src="uploads/' . htmlspecialchars($image['filename']) . '" alt="Imagen">';
                echo '<a href="delete.php?id=' . $image['id'] . '" class="delete-button">Eliminar</a>';
                echo '</div>';
            }
            ?>
        </div>
    </section>
</body>
</html>

