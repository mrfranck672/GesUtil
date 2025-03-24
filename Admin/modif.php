<?php
require '../conn.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo(" ID utilisateur manquant ");
}

$id = $_GET['id'];

// Récupérer l'utilisateur à modifier
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Mettre à jour l'utilisateur si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $emailuser = trim($_POST['emailuser']);
    $role_id = $_POST['role_id'];

    // Vérification email
    if (!filter_var($emailuser, FILTER_VALIDATE_EMAIL)) {
        echo " Adresse email invalide ";
    } else {
        // Mise à jour en base de données
        $update = $pdo->prepare("UPDATE user SET username = ?, emailuser = ?, role_id = ? WHERE id_user = ?");
        if ($update->execute([$username, $emailuser, $role_id, $id])) {
            header("Location: ../Admin/acceuilAdm.php.php"); // Retour au tableau de bord
            exit();
        } else {
            echo " Erreur lors de la mise à jour ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Modifier Utilisateur</h2>
        <form action="../Admin/acceuilAdm.php" method="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium">Nom</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="emailuser" value="<?= htmlspecialchars($user['emailuser']) ?>" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">Rôle</label>
                <select name="role_id" class="w-full px-4 py-2 border rounded-lg">
                    <option value="1" <?= $user['role_id'] == 1 ? "selected" : "" ?>>Admin</option>
                    <option value="2" <?= $user['role_id'] == 2 ? "selected" : "" ?>>Utilisateur</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Modifier</button>
        </form>
        <a href="../Admin/acceuilAdm.php" class="block text-center text-gray-500 mt-3">Retour</a>
    </div>
</body>
</html>
