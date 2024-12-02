<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$image_id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM images WHERE id = ? AND user_id = ?");
$stmt->execute([$image_id, $_SESSION['user_id']]);

header("Location: dashboard.php");
exit;
?>
