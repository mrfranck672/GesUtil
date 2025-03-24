<?php
require '../conn.php';
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Vérifie si l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID utilisateur invalide.");
}

$id_user = $_GET['id'];

// Récupérer le statut actuel
$stmt = $pdo->prepare("SELECT status FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilisateur non trouvé.");
}

// Inverser le statut (1 -> 0 ou 0 -> 1)
$new_status = $user['status'] == 1 ? 0 : 1;

// Mettre à jour le statut
$stmt = $pdo->prepare("UPDATE user SET status = ? WHERE id_user = ?");
if ($stmt->execute([$new_status, $id_user])) {
    header("Location: ../Admin/acceuilAdm.php"); // Redirige vers la liste après mise à jour
    exit();
} else {
    echo "Erreur lors de la mise à jour du statut.";
}
?>
