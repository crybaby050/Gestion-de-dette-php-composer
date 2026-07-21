<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Client;

/**
 * Gère la liste, la fiche et la création des clients.
 * Réservé au rôle 'admin'.
 */
class ClientController extends Controller
{
    private Client $clientModel;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->clientModel = new Client();
    }

    /**
     * Liste paginée des clients, avec filtre par statut et recherche par nom.
     * GET /clients?statut=solvable&recherche=diop&page=2
     */
    public function index(): void
    {
        $statut    = $_GET['statut'] ?? null;
        $recherche = $_GET['recherche'] ?? null;
        $page      = max(1, (int) ($_GET['page'] ?? 1));

        $clients = $this->clientModel->paginer($page, $statut ?: null, $recherche ?: null);
        $total   = $this->clientModel->compter($statut ?: null, $recherche ?: null);
        $nbPages = (int) ceil($total / Client::PAR_PAGE);

        $this->view('client/index', [
            'clients'   => $clients,
            'statut'    => $statut,
            'recherche' => $recherche,
            'page'      => $page,
            'nbPages'   => max(1, $nbPages),
            'statuts'   => Client::STATUTS,
        ]);
    }

    /**
     * Affiche la fiche d'un client : photo + infos + tableau des dettes.
     * GET /clients/show?id=3
     */
    public function show(?int $id = null): void
    {
        if ($id === null) {
            $this->redirect('/clients');
        }

        $client = $this->clientModel->findAvecDettes($id);
        if (!$client) {
            http_response_code(404);
            die('Client introuvable.');
        }

        $this->view('client/show', ['client' => $client]);
    }

    /**
     * Affiche le formulaire d'ajout d'un client.
     * GET /clients/create
     */
    public function create(): void
    {
        $this->view('client/create', ['erreurs' => []]);
    }

    /**
     * Traite la soumission du formulaire d'ajout (POST, multipart pour la photo).
     * POST /clients/store
     */
    public function store(): void
    {
        $erreurs = $this->validerFormulaire($_POST);

        if (!empty($erreurs)) {
            $this->view('client/create', ['erreurs' => $erreurs]);
            return;
        }

        $cheminPhoto = $this->traiterUploadPhoto($_FILES['photo_profil'] ?? null);

        $this->clientModel->creer([
            'nom'          => trim($_POST['nom']),
            'prenom'       => trim($_POST['prenom']),
            'telephone'    => trim($_POST['telephone']),
            'email'        => trim($_POST['email']),
            'mot_de_passe' => $_POST['mot_de_passe'],
            'photo_profil' => $cheminPhoto,
        ]);

        $this->redirect('/clients');
    }

    /**
     * Validation basique des champs du formulaire de création.
     */
    private function validerFormulaire(array $donnees): array
    {
        $erreurs = [];

        if (empty($donnees['nom']))          $erreurs[] = 'Le nom est requis.';
        if (empty($donnees['prenom']))       $erreurs[] = 'Le prénom est requis.';
        if (empty($donnees['email']) || !filter_var($donnees['email'], FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'Email invalide.';
        }
        if (empty($donnees['mot_de_passe']) || strlen($donnees['mot_de_passe']) < 6) {
            $erreurs[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        // Unicité de l'email
        if (empty($erreurs)) {
            $existant = $this->clientModel->findByEmail(trim($donnees['email']));
            if ($existant) {
                $erreurs[] = 'Cet email est déjà utilisé.';
            }
        }

        return $erreurs;
    }

    /**
     * Déplace la photo uploadée vers public/uploads/clients/ et retourne
     * le chemin relatif à stocker en base (ou null si aucune photo fournie).
     */
    private function traiterUploadPhoto(?array $fichier): ?string
    {
        if (!$fichier || $fichier['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionsAutorisees, true)) {
            return null;
        }

        $nomFichier = uniqid('client_', true) . '.' . $extension;
        $dossierDestination = __DIR__ . '/../../public/uploads/clients/';

        if (!is_dir($dossierDestination)) {
            mkdir($dossierDestination, 0755, true);
        }

        move_uploaded_file($fichier['tmp_name'], $dossierDestination . $nomFichier);

        return '/uploads/clients/' . $nomFichier;
    }
}