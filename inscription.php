<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Inscription </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $emailuser = isset($_POST['emailuser']) ? trim($_POST['emailuser']) : null;
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash du mot de passe
    $role_id = 2; // Exemple: rôle utilisateur (peut dépendre de ton système)
    $status = 1; // 1 = actif, 0 = inactif
    $createdat = date('Y-m-d H:i:s'); // Date actuelle

    if (!$username || !$emailuser || !$password) {
        echo " Tous les champs sont obligatoires ! ";
    }

    // Vérification de l'email valide
    if (!filter_var($emailuser, FILTER_VALIDATE_EMAIL)) {
        die(" Adresse email invalide ");
    }

    // Hashage du mot de passe
    // $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insérer les données dans la base de données
    $stmt = $pdo->prepare("INSERT INTO user (username, emailuser, password, role_id, status, createdat) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt-> execute([$username, $emailuser, $password, $role_id, $status, $createdat])) {
        header("Location: login.php"); // Redirection vers la page de connexion
        exit();
    } else {
        echo " Erreur lors de l'inscription ";
    }
}
?>

<body class="bg-green-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-2"> Formulaire d'inscripton </h2>
        <form action="inscription.php" method="POST">

        <div class="mb-2">
                <label for="nom" class="block text-gray-600 text-sm font-medium mb-2"> Nom </label>
                <input type="text" id="nom" name="username" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="mb-2">
                <label for="email" class="block text-gray-600 text-sm font-medium mb-2"> Email </label>
                <input type="email" id="email" name="emailuser" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="mb-2">
                <label for="password" class="block text-gray-600 text-sm font-medium mb-2"> Mot de passe </label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"> S'inscrire </button>
        </form>
       <p class="text-gray-600 text-sm text-center mt-4"> <a href="" class="text-blue-500 hover:underline"> Abandonner </a></p>
    </div>
</body>
</html>


