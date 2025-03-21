<?php
session_start(); // Toujours en premier

require 'conn.php'; // Vérifie que ce fichier contient bien la connexion PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Préparer la requête SQL pour récupérer l'utilisateur

    $stmt = $pdo->prepare("SELECT * FROM user WHERE emailuser = ?");
    $stmt->execute([$email]); 
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['emailuser'];
            header("Location: Client/acceuilCli.php");
            exit();
        } else {
            $error = " Mot de passe incorrect ";
        }
    } else {
        $error = " Aucun compte trouvé avec cet email ";
    }
    

    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['emailuser'];
    
        // Récupérer l'IP du client
        $ip = $_SERVER['REMOTE_ADDR'];
    
        // Insérer dans l'historique des connexions
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, login_time, logout_time) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $login_time, $logout_time]);
    
        header("Location: Client/acceuilCli.php");
        exit();
    }
    
}
?>
