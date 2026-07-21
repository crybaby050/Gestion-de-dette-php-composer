<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-sm">
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">Connexion</h1>

        <?php if (!empty($erreur)): ?>
            <div class="bg-red-100 text-red-700 text-sm px-4 py-2 rounded mb-4">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mot de passe</label>
                <input type="password" name="mot_de_passe" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <button type="submit"
                    class="w-full bg-slate-800 text-white py-2 rounded hover:bg-slate-700 transition">
                Se connecter
            </button>
        </form>
    </div>

</body>
</html>