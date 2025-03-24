<?php
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $emailuser = trim($_POST['emailuser']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // admin ou user
    $admin_code = isset($_POST['admin_code']) ? trim($_POST['admin_code']) : null;

    $role_id = 2; // Rôle "Utilisateur" par défaut
    $status = 1;
    $createdat = date('Y-m-d H:i:s');

    if (!$username || !$emailuser || !$_POST['password']) {
        die(" Veuillez remplir Tous les champs !");
    }

    if (!filter_var($emailuser, FILTER_VALIDATE_EMAIL)) {
        die(" Adresse email invalide ");
    }

    // Vérification du rôle
    if ($role === "admin") {
        if ($admin_code !== "6789") { // Code secret à changer
            die("Code Admin incorrect !");
        }
        $role_id = 1; // Rôle "Admin"
    }

    $stmt = $pdo->prepare("INSERT INTO user (username, emailuser, password, role_id, status, createdat) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$username, $emailuser, $password, $role_id, $status, $createdat])) {
        header("Location: login.php");
        exit();
    } else {
        echo "Erreur lors de l'inscription";
    }
}
?>