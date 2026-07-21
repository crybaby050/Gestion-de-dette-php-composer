# Gestion Clients & Dettes

Application web PHP orientée objet (architecture MVC maison) pour gérer
des clients et le suivi de leurs dettes.

## Stack technique

- PHP >= 8.1, PDO (pgsql)
- PostgreSQL
- Tailwind CSS (via CDN)
- Architecture MVC maison avec namespaces PSR-4 (`App\Controllers`, `App\Models`, `App\Core`)
- Connexion base de données en Singleton (`App\Core\Database`)

## Installation

1. Créer la base et les tables :
```bash
   psql -U postgres -f database/schema.sql
```

2. Configurer l'accès à la base dans `config/database.php`.

3. (Optionnel) Installer les dépendances Composer :
```bash
   composer install
   composer dump-autoload
```
   Le projet fonctionne aussi sans Composer grâce à l'autoloader manuel
   présent dans `public/index.php`.

4. Créer le dossier d'upload des photos et le rendre accessible en écriture :
```bash
   mkdir -p public/uploads/clients
   chmod -R 775 public/uploads
```

5. Démarrer un serveur local :
```bash
   php -S localhost:8000 -t public
```

## Comptes de test

| Rôle   | Email                     | Mot de passe |
|--------|---------------------------|--------------|
| Admin  | admin@gcd.sn              | admin123     |
| Client | moussa.diop@example.com   | client123    |

## Fonctionnalités actuelles (entité Client)

- Connexion / déconnexion (admin et client, un seul formulaire de login).
- Espace admin :
  - Liste des clients avec pagination (5 par page).
  - Filtre par statut (`nouveau`, `solvable`, `non_solvable`).
  - Recherche par nom / prénom.
  - Fiche détaillée d'un client (photo + informations + tableau de ses dettes).
  - Ajout d'un client avec upload de photo de profil (statut initial toujours `nouveau`).

## Structure

Voir l'arborescence dans la documentation du projet (dossiers `app/`, `views/`, `routes/`, `config/`, `database/`).

## Prochaines étapes envisagées

- Espace client (`/mon-espace`, `/mes-dettes`).
- CRUD complet sur les dettes côté admin.
- Modification / suppression de client.