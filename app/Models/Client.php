<?php
namespace App\Models;

/**
 * Model spécialisé pour les utilisateurs ayant le rôle 'client'.
 * Hérite d'Utilisateur pour réutiliser la logique commune (auth),
 * et ajoute tout ce qui concerne la liste, le filtre, la recherche,
 * la pagination et la fiche détaillée d'un client.
 */
class Client extends Utilisateur
{
    public const PAR_PAGE = 5;

    /**
     * Statuts possibles pour un client.
     */
    public const STATUTS = ['nouveau', 'solvable', 'non_solvable'];

    /**
     * Récupère une page de clients, avec filtre optionnel par statut
     * et recherche optionnelle par nom/prénom.
     *
     * @param int         $page      Numéro de page (commence à 1)
     * @param string|null $statut    'nouveau' | 'solvable' | 'non_solvable' | null (= tous)
     * @param string|null $recherche Terme recherché dans nom/prénom
     */
    public function paginer(int $page = 1, ?string $statut = null, ?string $recherche = null): array
    {
        [$where, $params] = $this->construireFiltre($statut, $recherche);

        $offset = ($page - 1) * self::PAR_PAGE;

        $sql = "SELECT id, nom, prenom, telephone, email, photo_profil, statut, created_at
                FROM {$this->table}
                {$where}
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', self::PAR_PAGE, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre total de clients correspondant au filtre/recherche
     * (nécessaire pour calculer le nombre de pages de la pagination).
     */
    public function compter(?string $statut = null, ?string $recherche = null): int
    {
        [$where, $params] = $this->construireFiltre($statut, $recherche);

        $sql = "SELECT COUNT(*) AS total FROM {$this->table} {$where}";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return (int) $stmt->fetch()['total'];
    }

    /**
     * Construit la clause WHERE et les paramètres liés en fonction
     * du filtre de statut et de la recherche par nom.
     * On part toujours de role = 'client'.
     */
    private function construireFiltre(?string $statut, ?string $recherche): array
    {
        $conditions = ["role = 'client'"];
        $params = [];

        if ($statut !== null && in_array($statut, self::STATUTS, true)) {
            $conditions[] = 'statut = :statut';
            $params[':statut'] = $statut;
        }

        if ($recherche !== null && trim($recherche) !== '') {
            $conditions[] = "(nom ILIKE :recherche OR prenom ILIKE :recherche)";
            $params[':recherche'] = '%' . trim($recherche) . '%';
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        return [$where, $params];
    }

    /**
     * Récupère un client par id, avec la liste de ses dettes associées.
     */
    public function findAvecDettes(int $id): ?array
    {
        $client = $this->find($id);
        if (!$client || $client['role'] !== 'client') {
            return null;
        }

        $detteModel = new Dette();
        $client['dettes'] = $detteModel->parClient($id);

        return $client;
    }

    /**
     * Crée un nouveau client. Le statut est toujours 'nouveau' à la création
     * (règle métier : un client ajouté par l'admin démarre toujours 'nouveau').
     */
    public function creer(array $donnees): int
    {
        $sql = "INSERT INTO {$this->table} (nom, prenom, telephone, email, mot_de_passe, role, photo_profil, statut)
                VALUES (:nom, :prenom, :telephone, :email, :mot_de_passe, 'client', :photo_profil, 'nouveau')
                RETURNING id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nom'          => $donnees['nom'],
            ':prenom'       => $donnees['prenom'],
            ':telephone'    => $donnees['telephone'],
            ':email'        => $donnees['email'],
            ':mot_de_passe' => password_hash($donnees['mot_de_passe'], PASSWORD_BCRYPT),
            ':photo_profil' => $donnees['photo_profil'] ?? null,
        ]);

        return (int) $stmt->fetchColumn();
    }
}