<?php
require '../conn.php';
session_start();

// Vérifier si l'utilisateur est connecté (ajouter un contrôle admin si nécessaire)
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id_user, username, emailuser, role_id, status FROM user");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- <title>Tableau de Bord</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<p class="flex justify-end"> Ajouter un nouveau utilisateur <br> <button class="bg-blue-500 text-white px-3 py-1 rounded"> + </button> </p>
<body class="bg-gray-100 p-10">
    <h1 class="text-3xl text-center mb-6"> Liste des Utilisateurs </h1>

    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="px-4 py-2"> ID </th>
                <th class="px-4 py-2"> Nom </th>
                <th class="px-4 py-2"> Email </th>
                <th class="px-4 py-2"> Rôle </th>
                <th class="px-4 py-2"> Status </th>
                <th class="px-4 py-2"> Actions </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr class="border-b">
                <td class="px-4 py-2"><?= $user['id_user'] ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($user['emailuser']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($user['role_id']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($user['status'] )?></td>
                <td class="px-4 py-2">
                    <a href="modif.php?id=<?= $user['id_user'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded">Modifier</a>
                    <a href="suppControler.php?id=<?= $user['id_user'] ?>" class="bg-red-500 text-white px-3 py-1 rounded" onclick="return confirm(' Voulez Supprimer cet utilisateur ?')"> Supprimer </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
