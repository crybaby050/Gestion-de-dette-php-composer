<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-slate-800">Liste des clients</h1>
    <a href="<?= BASE_URL ?>/clients/create"
       class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-700 transition">
       + Ajouter un client
    </a>
</div>

<!-- Filtres : statut + recherche par nom -->
<form method="GET" action="<?= BASE_URL ?>/clients" class="bg-white p-4 rounded shadow mb-6 flex gap-4 items-end">
    <div>
        <label class="block text-sm font-medium mb-1">Statut</label>
        <select name="statut" class="border border-gray-300 rounded px-3 py-2">
            <option value="">Tous</option>
            <?php foreach ($statuts as $s): ?>
                <option value="<?= $s ?>" <?= $statut === $s ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $s)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium mb-1">Recherche par nom</label>
        <input type="text" name="recherche" value="<?= htmlspecialchars($recherche ?? '') ?>"
               placeholder="Nom ou prénom..."
               class="w-full border border-gray-300 rounded px-3 py-2">
    </div>
    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-700">
        Filtrer
    </button>
</form>

<!-- Tableau des clients -->
<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-3">Photo</th>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Téléphone</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-6">Aucun client trouvé.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $c): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <img src="<?= $c['photo_profil'] ? BASE_URL . $c['photo_profil'] : 'https://ui-avatars.com/api/?name=' . urlencode($c['prenom'] . '+' . $c['nom']) ?>"
                                 class="w-10 h-10 rounded-full object-cover" alt="Photo">
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($c['telephone'] ?? '-') ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($c['email']) ?></td>
                        <td class="px-4 py-3">
                            <?php
                                $badge = match ($c['statut']) {
                                    'solvable'     => 'bg-green-100 text-green-700',
                                    'non_solvable' => 'bg-red-100 text-red-700',
                                    default        => 'bg-yellow-100 text-yellow-700',
                                };
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-medium <?= $badge ?>">
                                <?= ucfirst(str_replace('_', ' ', $c['statut'])) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="<?= BASE_URL ?>/clients/show?id=<?= $c['id'] ?>"
                               class="text-slate-700 font-medium hover:underline">Voir la fiche</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($nbPages > 1): ?>
<div class="mt-6 flex justify-center gap-2">
    <?php for ($p = 1; $p <= $nbPages; $p++): ?>
        <?php
            $params = http_build_query(['statut' => $statut, 'recherche' => $recherche, 'page' => $p]);
        ?>
        <a href="<?= BASE_URL ?>/clients?<?= $params ?>"
           class="px-3 py-1 rounded border <?= $p === $page ? 'bg-slate-800 text-white' : 'bg-white text-slate-700 hover:bg-gray-100' ?>">
            <?= $p ?>
        </a>
    <?php endfor; ?>
</div>
<?php endif; ?>