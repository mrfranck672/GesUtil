<?php
session_start();
require 'conn.php'; // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM user WHERE emailuser = ?");
    $stmt->execute([$email]); 
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id_user'],
            'email' => $user['emailuser'],
            'role' => $user['role_id']
        ];

        // Sauvegarder la connexion dans l'historique
        $ip = $_SERVER['REMOTE_ADDR'];
        $login_time = date('Y-m-d H:i:s');

        // Insérer l'entrée de connexion et récupérer l'ID
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, login_time, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$user['id_user'], $login_time, $ip]);

        $_SESSION['session_id'] = $pdo->lastInsertId(); // Stocke l'ID pour mise à jour du logout

        // Redirection en fonction du rôle
        if ($user['role_id'] == 1) {
            header("Location: Admin/acceuilAdm.php"); // Admin
        } else {
            header("Location: Client/acceuilCli.php"); // Utilisateur
        }
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}

// GESTION DE LA DÉCONNEXION
if (isset($_GET['logout'])) {
    if (isset($_SESSION['session_id'])) {
        $logout_time = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("UPDATE sessions SET logout_time = ? WHERE id = ?");
        $stmt->execute([$logout_time, $_SESSION['session_id']]);
    }

    session_destroy(); // Détruire la session
    header("Location: login.php");
    exit();
}
?>