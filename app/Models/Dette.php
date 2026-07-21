<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model représentant une dette rattachée à un client.
 */
class Dette extends Model
{
    protected string $table = 'dettes';

    public const STATUTS = ['solde', 'non_solde'];

    /**
     * Récupère toutes les dettes d'un client donné, triées de la plus
     * récente à la plus ancienne.
     */
    public function parClient(int $clientId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE client_id = ? ORDER BY date_creation DESC"
        );
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    /**
     * Crée une nouvelle dette pour un client.
     */
    public function creer(int $clientId, string $libelle, float $montant, ?string $dateEcheance = null): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (client_id, libelle, montant, date_echeance)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$clientId, $libelle, $montant, $dateEcheance]);
    }

    /**
     * Marque une dette comme soldée.
     */
    public function marquerSoldee(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET statut = 'solde' WHERE id = ?");
        return $stmt->execute([$id]);
    }
}