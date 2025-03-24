<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Inscription </title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Identifier le rôle lors de l'inscription -->
    <script>
        function checkRole() {
            var role = document.getElementById("role").value;
            var adminCodeField = document.getElementById("adminCodeField");
            adminCodeField.style.display = (role == "admin") ? "block" : "none";
        }
    </script>
</head>

<body class="bg-green-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-2"> Formulaire d'inscription </h2>
        <form action="inscriptionController.php" method="POST">

            <div class="mb-2">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Nom </label>
                <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="mb-2">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Email </label>
                <input type="email" name="emailuser" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="mb-2">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Mot de passe </label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="mb-2">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Rôle </label>
                <select name="role" id="role" onchange="checkRole()" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="user">Utilisateur</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="mb-2" id="adminCodeField" style="display: none;">
                <label class="block text-gray-600 text-sm font-medium mb-2"> Code Admin </label>
                <input type="password" name="admin_code" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"> S'inscrire </button>
        </form>
    </div>
</body>
</html>
