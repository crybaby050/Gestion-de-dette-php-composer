<h1 class="text-2xl font-bold text-slate-800 mb-6">Ajouter un client</h1>

<div class="bg-white rounded shadow p-6 max-w-lg">
    <?php if (!empty($erreurs)): ?>
        <div class="bg-red-100 text-red-700 text-sm px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php foreach ($erreurs as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/clients/store" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nom</label>
            <input type="text" name="nom" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Prénom</label>
            <input type="text" name="prenom" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Téléphone</label>
            <input type="text" name="telephone" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Mot de passe</label>
            <input type="password" name="mot_de_passe" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Photo de profil</label>
            <input type="file" name="photo_profil" accept="image/*" class="w-full">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-700">
                Enregistrer
            </button>
            <a href="<?= BASE_URL ?>/clients" class="px-4 py-2 rounded border hover:bg-gray-50">
                Annuler
            </a>
        </div>
    </form>
</div>