<?php
$host = "localhost"; // Serveur
$dbname = "gestion_utlisateurs"; // Nom de la base de données
$username = "root"; // Utilisateur MySQL
$password = ""; // Mot de passe (vide sur localhost)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
// echo " Connexion réussie ";

?>
