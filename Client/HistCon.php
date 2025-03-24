<?php
session_start();
require '../conn.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur non trouvé";
    exit();
}

// Mise à jour des informations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = trim($_POST['username']);
    $email = trim($_POST['emailuser']);
    $role_id = trim($_POST['role_id']);
    $status = trim($_POST['status']);

    // Vérification des données
    if (empty($nom) || empty($email)) {
        $error = "Le nom et l'email sont obligatoires.";
    } else {
        // Mise à jour des informations dans la base de données
        $updateStmt = $pdo->prepare("UPDATE user SET username = ?, emailuser = ?, role_id = ?, status = ? WHERE id_user = ?");
        $updateStmt->execute([$nom, $email, $role_id, $status, $_SESSION['user']['id']]);

        // Rafraîchir les données dans la session
        $_SESSION['user']['email'] = $email; // Mise à jour dans la session
        $_SESSION['user']['role'] = $role_id;

        // Rediriger pour éviter la soumission multiple du formulaire
        header("Location: acceuilCli.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Mon Profil</title>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Mon Profil</h2>

          <!-- Vérification de l'URL pour afficher le message de succès -->
          <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="text-green-500 mb-4">Informations mises à jour avec succès.</p>
        <?php endif; ?>

        <!-- Informations actuelles de l'utilisateur -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold">Informations actuelles</h3>
            <div class="mb-2">
                <strong class="text-gray-700">Nom:</strong> <?= htmlspecialchars($user['username']); ?>
            </div>
            <div class="mb-2">
                <strong class="text-gray-700">Email:</strong> <?= htmlspecialchars($user['emailuser']); ?>
            </div>
            <div class="mb-2">
                <strong class="text-gray-700">Rôle:</strong> <?= htmlspecialchars($user['role_id']); ?>
            </div>
            <div class="mb-2">
                <strong class="text-gray-700">Statut:</strong> <?= htmlspecialchars($user['status']); ?>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <form action="acceuilCli.php" method="POST">
            <h3 class="text-lg font-semibold mb-2">Modifier les informations</h3>
            
            <div class="mb-4">
                <label class="block text-gray-700">Nom :</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Email :</label>
                <input type="email" name="emailuser" value="<?= htmlspecialchars($user['emailuser']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Rôle :</label>
                <input type="text" name="role_id" value="<?= htmlspecialchars($user['role_id']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Statut :</label>
                <input type="text" name="status" value="<?= htmlspecialchars($user['status']); ?>" class="w-full p-2 border rounded-lg">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Mettre à jour</button>
        </form>

        <div class="mt-4">
            <a href="../ControllerLogin.php?logout=true" class="text-red-500">Déconnexion</a>
        </div>
    </div>

</body>
</html>
