<?php
require '../conn.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    header("Location: acceuilAdm.php");
    exit();
}

$id = $_GET['user_id'];

$stmt = $pdo->prepare("DELETE FROM user WHERE user_id = ?");
if ($stmt->execute([$id])) {
    header("Location: acceuilAdm.php");
    exit();
} else {
    echo "Erreur lors de la suppression.";
}
?>
