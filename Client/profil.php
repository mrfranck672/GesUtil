<?php
session_start();
require '../conn.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


// Récupérer l'historique des connexions
$stmt = $pdo->query("SELECT * FROM sessions ORDER BY user_id, login_time, logout_time DESC");
$historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Historique des Connexions</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Email</th>
                    <th class="border p-2"> Connexion </th>
                    <th class="border p-2"> Deconnexion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $log): ?>
                    <tr class="border-b">
                        <td class="border p-2"><?= htmlspecialchars($log['user_id']); ?></td>
                        <td class="border p-2"><?= htmlspecialchars($log['login_time']); ?></td>
                        <td class="border p-2"><?= htmlspecialchars($log['logout_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
