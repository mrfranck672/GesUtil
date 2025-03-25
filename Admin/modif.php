<?php
require '../conn.php';
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Vérifier si l'ID est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur manquant";
    header("Location: acceuilAdm.php");
    exit();
}

$id = $_GET['id'];

// Récupérer l'utilisateur à modifier
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Utilisateur introuvable";
    header("Location: acceuilAdm.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $emailuser = trim($_POST['emailuser']);
    $role_id = (int)$_POST['role_id'];
    $status = isset($_POST['status']) ? 1 : 0;

    // Validation
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis";
    }

    if (!filter_var($emailuser, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide";
    }

    if (empty($errors)) {
        try {
            // Mise à jour en base de données
            $update = $pdo->prepare("UPDATE user SET username = ?, emailuser = ?, role_id = ?, status = ? WHERE id_user = ?");
            
            if ($update->execute([$username, $emailuser, $role_id, $status, $id])) {
                $_SESSION['success'] = "Utilisateur mis à jour avec succès";
                header("Location: acceuilAdm.php");
                exit();
            } else {
                $errors[] = "Erreur lors de la mise à jour";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données: " . $e->getMessage();
        }
    }
    
    $_SESSION['errors'] = $errors;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-500 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Modifier Utilisateur</h2>
            </div>
            
            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-4 mt-4" role="alert">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['errors']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="p-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Nom d'utilisateur
                    </label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="emailuser">
                        Email
                    </label>
                    <input type="email" id="emailuser" name="emailuser" value="<?= htmlspecialchars($user['emailuser']) ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role_id">
                        Rôle
                    </label>
                    <select id="role_id" name="role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Administrateur</option>
                        <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Utilisateur</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="status" class="form-checkbox h-5 w-5 text-blue-600" <?= $user['status'] == 1 ? 'checked' : '' ?>>
                        <span class="text-gray-700">Compte activé</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                    <a href="acceuilAdm.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>