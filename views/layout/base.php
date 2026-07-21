<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Clients & Dettes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php $utilisateur = \App\Core\Auth::user(); ?>

<div class="flex min-h-screen">

    <!-- Sidebar : les liens changent selon le rôle de l'utilisateur connecté -->
    <aside class="w-64 bg-slate-800 text-white flex flex-col">
        <div class="px-6 py-5 text-xl font-bold border-b border-slate-700">
            Gestion Clients
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            <?php if ($utilisateur && $utilisateur['role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/clients"
                   class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                   Clients
                </a>
                <a href="<?= BASE_URL ?>/clients/create"
                   class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                   Ajouter un client
                </a>
            <?php elseif ($utilisateur && $utilisateur['role'] === 'client'): ?>
                <a href="<?= BASE_URL ?>/mon-espace"
                   class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                   Mon espace
                </a>
                <a href="<?= BASE_URL ?>/mes-dettes"
                   class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                   Mes dettes
                </a>
            <?php endif; ?>
        </nav>

        <?php if ($utilisateur): ?>
        <div class="px-4 py-4 border-t border-slate-700 text-sm">
            <p class="mb-2">Connecté : <span class="font-semibold"><?= htmlspecialchars($utilisateur['prenom']) ?></span></p>
            <a href="<?= BASE_URL ?>/logout" class="text-red-400 hover:text-red-300">Se déconnecter</a>
        </div>
        <?php endif; ?>
    </aside>

    <!-- Contenu de la page -->
    <main class="flex-1 p-8">
        <?= $content ?>
    </main>

</div>
</body>
</html>