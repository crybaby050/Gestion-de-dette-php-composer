<h1 class="text-2xl font-bold text-slate-800 mb-6">Fiche client</h1>

<div class="bg-white rounded shadow p-6 flex gap-8 mb-8">
    <!-- Photo à gauche -->
    <div class="shrink-0">
        <img src="<?= $client['photo_profil'] ? BASE_URL . $client['photo_profil'] : 'https://ui-avatars.com/api/?size=200&name=' . urlencode($client['prenom'] . '+' . $client['nom']) ?>"
             class="w-40 h-40 rounded-full object-cover border-4 border-slate-100" alt="Photo de profil">
    </div>

    <!-- Informations à droite -->
    <div class="flex-1">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">
            <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
        </h2>
        <dl class="grid grid-cols-2 gap-y-3 text-sm">
            <dt class="text-gray-500">Téléphone</dt>
            <dd><?= htmlspecialchars($client['telephone'] ?? '-') ?></dd>

            <dt class="text-gray-500">Email</dt>
            <dd><?= htmlspecialchars($client['email']) ?></dd>

            <dt class="text-gray-500">Statut</dt>
            <dd>
                <?php
                    $badge = match ($client['statut']) {
                        'solvable'     => 'bg-green-100 text-green-700',
                        'non_solvable' => 'bg-red-100 text-red-700',
                        default        => 'bg-yellow-100 text-yellow-700',
                    };
                ?>
                <span class="px-2 py-1 rounded text-xs font-medium <?= $badge ?>">
                    <?= ucfirst(str_replace('_', ' ', $client['statut'])) ?>
                </span>
            </dd>

            <dt class="text-gray-500">Client depuis</dt>
            <dd><?= (new DateTime($client['created_at']))->format('d/m/Y') ?></dd>
        </dl>
    </div>
</div>

<!-- Tableau des dettes -->
<h3 class="text-lg font-semibold text-slate-800 mb-3">Dettes</h3>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-3">Libellé</th>
                <th class="px-4 py-3">Montant</th>
                <th class="px-4 py-3">Date de création</th>
                <th class="px-4 py-3">Échéance</th>
                <th class="px-4 py-3">Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($client['dettes'])): ?>
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-6">Aucune dette enregistrée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($client['dettes'] as $d): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3"><?= htmlspecialchars($d['libelle']) ?></td>
                        <td class="px-4 py-3"><?= number_format($d['montant'], 0, ',', ' ') ?> FCFA</td>
                        <td class="px-4 py-3"><?= (new DateTime($d['date_creation']))->format('d/m/Y') ?></td>
                        <td class="px-4 py-3"><?= $d['date_echeance'] ? (new DateTime($d['date_echeance']))->format('d/m/Y') : '-' ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium <?= $d['statut'] === 'solde' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $d['statut'] === 'solde' ? 'Soldé' : 'Non soldé' ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="<?= BASE_URL ?>/clients" class="text-slate-700 hover:underline">&larr; Retour à la liste</a>
</div>