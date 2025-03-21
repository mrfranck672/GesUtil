<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6"> Connexion </h2>
        <form action="ControllerLogin.php" method="POST">

            <div class="mb-4">
                <label for="email" class="block text-gray-600 text-sm font-medium mb-2"> Email </label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-600 text-sm font-medium mb-2"> Mot de passe </label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="flex justify-between items-center mb-4">
                <label class="inline-flex items-center text-gray-600 text-sm">
                    <input type="checkbox" class="form-checkbox text-blue-500">
                    <span class="ml-2"> Se souvenir de moi </span>
                </label>
                <!-- <a href="#" class="text-blue-500 text-sm hover:underline"> Mot de passe oublié? </a> -->
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"> Se connecter </button>
        </form>
        <p class="text-gray-600 text-sm text-center mt-4"> Pas de compte ? <a href="inscription.php" class="text-blue-500 hover:underline"> Créer un compte </a></p>
    </div>
</body>
</html>
