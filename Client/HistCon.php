<?php
session_start();
require '../conn.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur

$email = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE emailuser = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// mise à jour des informations

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['username']);
    $email = trim($_POST['emailuser']);
    $password = trim($_POST['password']);
    $role_id = trim($_POST['role_id']);
    $status = trim($_POST['status']);
    
    // Mise à jour en base
    $updateStmt = $pdo->prepare("UPDATE user SET username = ?, role_id = ?, status = ? WHERE emailclt = ?");
    $updateStmt->execute([$nom, $email]);

    // Rafraîchir les données affichées
    header("Location: acceuilCli.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4"> Mon Profil </h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="text-green-500"> Informations mises à jour avec succès </p>
        <?php endif; ?>

        <form action="acceuilCli.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700"> Nom :</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700"> Email :</label>
                <input type="email" value="<?= htmlspecialchars($user['emailuser']); ?>" disabled class="w-full p-2 border rounded-lg bg-gray-200">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700"> Role :</label>
                <input type="text" name="role_id" value="<?= htmlspecialchars($user['role_id']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700"> Status :</label>
                <input type="text" name="status" value="<?= htmlspecialchars($user['status']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Mettre à jour</button>
        </form>
        
        <div class="mt-4">
            <a href="logout.php" class="text-red-500">Déconnexion</a>
        </div>
    </div>

</body>
</html>
