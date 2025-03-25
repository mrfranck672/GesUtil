<?php
require '../conn.php';
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $userId = $_POST['user_id'];
        
        switch ($_POST['action']) {
            case 'modif':
                // Redirection vers la page de modification
                header("Location: modif.php?id=" . $userId);
                exit();
                break;
                
            case 'suppControler':
                // Suppression de l'utilisateur
                $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = ?");
                $stmt->execute([$userId]);
                $_SESSION['message'] = "Utilisateur supprimé avec succès";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
                break;
                
            case 'status':
                // Changement de statut (actif/inactif)
                $stmt = $pdo->prepare("SELECT status FROM user WHERE id_user = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
                $newStatus = $user['status'] == 1 ? 0 : 1;
                
                $stmt = $pdo->prepare("UPDATE user SET status = ? WHERE id_user = ?");
                $stmt->execute([$newStatus, $userId]);
                // $_SESSION['message'] = " Utilisateur mis à jour";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
                break;
        }
    }
}

// Récupération de tous les utilisateurs
$stmt = $pdo->query("SELECT id_user, username, emailuser, role_id, status FROM user");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function checkRole() {
            var role = document.getElementById("role").value;
            var adminCodeField = document.getElementById("adminCodeField");
            adminCodeField.style.display = (role == "admin") ? "block" : "none";
        }
        
        function performAction(userId) {
            const select = document.getElementById(`action_${userId}`);
            const action = select.value;
            
            if (!action) {
                alert(" Veuillez sélectionner une action ");
                return;
            }
            
            // Création d'un formulaire dynamique pour envoyer les données
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = action;
            
            const inputUserId = document.createElement('input');
            inputUserId.type = 'hidden';
            inputUserId.name = 'user_id';
            inputUserId.value = userId;
            
            form.appendChild(inputAction);
            form.appendChild(inputUserId);
            document.body.appendChild(form);
            
            // Confirmation pour la suppression
            if (action === 'suppControler') {
                if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
                    form.submit();
                }
            } else {
                form.submit();
            }
        }
    </script>
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-7xl mx-auto">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['message'] ?></span>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl"> Liste des Utilisateurs </h1>
            <button id="openModalButton" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"> + Ajouter un utilisateur </button>
        </div>

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
                <tr class="border-b text-center hover:bg-gray-50">
                    <td class="px-4 py-2"><?= $user['id_user'] ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($user['emailuser']) ?></td>
                    <td class="px-4 py-2"><?= ($user['role_id'] == 1) ? "Admin" : "Utilisateur" ?></td>
                    <td class="px-4 py-2">
                        <?= $user['status'] == 1 ? "<span class='text-green-500'>Actif</span>" : "<span class='text-red-500'>Inactif</span>" ?>
                    </td>
                    <td class="px-4 py-2">
                        <select id="action_<?= $user['id_user'] ?>" class="px-2 py-1 border rounded">
                            <option value=""> Sélectionner une action </option>
                            <option value="modif"> Modifier </option>
                            <option value="suppControler"> Supprimer </option>
                            <option value="status"><?= $user['status'] == 1 ? "Désactiver" : "Activer" ?>
                        </option>
                        </select>
                        <button onclick="performAction(<?= $user['id_user'] ?>)" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded ml-2"> Exécuter </button>
                    </td> 
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

      <!-- Modal -->
      <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4"> Ajouter un utilisateur </h2>
            <form action="AddController.php" method="POST" id="addUserForm">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Nom</label>
                    <input type="text" name="username" id="username" required class="w-full p-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="emailuser" id="email" required class="w-full p-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Mot de passe</label>
                    <input type="password" name="password" id="password" required class="w-full p-2 border rounded-lg">
                </div>

                <div class="mb-4">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Rôle </label>
                <select name="role" id="role" onchange="checkRole()" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="user"> Utilisateur </option>
                    <option value="admin"> Admin </option>
                </select>
            </div>

            <div class="mb-2" id="adminCodeField" style="display: none;">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Code Admin </label>
                <input type="password" name="admin_code" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Ajouter</button>
            </form>

            <button id="closeModalButton" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg">Fermer</button>
        </div>
    </div>

    <script>
        // Ouvrir le modal
        document.getElementById('openModalButton').addEventListener('click', function() {
            document.getElementById('userModal').classList.remove('hidden');
        });

        // Fermer le modal
        document.getElementById('closeModalButton').addEventListener('click', function() {
            document.getElementById('userModal').classList.add('hidden');
        });
    </script>

</body>
</html>
