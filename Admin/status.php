<?php
require '../conn.php';
session_start();

// Vérifie si l'utilisateur est connecté et a les droits admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    $_SESSION['error'] = "Accès non autorisé";
    header("Location: ../login.php");
    exit();
}

// Vérifie si l'ID est fourni et valide
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur invalide";
    header("Location: acceuilAdm.php");
    exit();
}

$id_user = (int)$_GET['id'];

try {
    // Récupérer le statut actuel
    $stmt = $pdo->prepare("SELECT status, username FROM user WHERE id_user = ?");
    $stmt->execute([$id_user]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception(" Utilisateur non trouvé ");
    }

    // Inverser le statut (1 -> 0 ou 0 -> 1)
    $new_status = $user['status'] == 1 ? 0 : 1;

    // Mettre à jour le statut
    $stmt = $pdo->prepare("UPDATE user SET status = ? WHERE id_user = ?");
    if ($stmt->execute([$new_status, $id_user])) {
        $action = $new_status == 1 ? "activé" : "désactivé";
        $_SESSION['success'] = "Utilisateur '{$user['username']}' a été $action avec succès";
    } else {
        throw new Exception(" Erreur lors de la mise à jour du statut ");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: acceuilAdm.php");
exit();
?>