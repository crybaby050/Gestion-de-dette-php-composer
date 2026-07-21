<?php
namespace App\Models;

use App\Core\Model;

/**
 * Model de base représentant un utilisateur (admin ou client).
 * Regroupe la logique commune d'authentification.
 */
class Utilisateur extends Model
{
    protected string $table = 'utilisateurs';

    /**
     * Recherche un utilisateur par son email (utilisé pour le login).
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Vérifie un mot de passe en clair contre le hash stocké.
     */
    public function verifyPassword(string $motDePasseClair, string $hash): bool
    {
        return password_verify($motDePasseClair, $hash);
    }
}