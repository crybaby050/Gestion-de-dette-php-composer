<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Utilisateur;

/**
 * Gère la connexion et la déconnexion, pour l'admin comme pour le client.
 */
class AuthController extends Controller
{
    private Utilisateur $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin(): void
    {
        // Si déjà connecté, on redirige directement selon le rôle.
        if (Auth::check()) {
            $this->redirectSelonRole();
        }

        $this->viewOnly('auth/login', ['erreur' => null]);
    }

    /**
     * Traite la soumission du formulaire de connexion (POST).
     */
    public function login(): void
    {
        $email      = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $utilisateur = $this->utilisateurModel->findByEmail($email);

        if (!$utilisateur || !$this->utilisateurModel->verifyPassword($motDePasse, $utilisateur['mot_de_passe'])) {
            $this->viewOnly('auth/login', ['erreur' => 'Email ou mot de passe incorrect.']);
            return;
        }

        Auth::login($utilisateur);
        $this->redirectSelonRole();
    }

    /**
     * Déconnecte l'utilisateur et retourne à la page de login.
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }

    /**
     * Redirige vers l'espace correspondant au rôle de l'utilisateur connecté.
     */
    private function redirectSelonRole(): void
    {
        $role = Auth::user()['role'];
        if ($role === 'admin') {
            $this->redirect('/clients');
        } else {
            $this->redirect('/mon-espace'); // à construire plus tard côté client
        }
    }
}