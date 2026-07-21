-- =========================================================
-- ÉTAPE 1 : Création de la base de données
-- À exécuter avec un rôle ayant le droit CREATEDB, ex :
--   psql -U postgres -f database/schema.sql
-- =========================================================
-- Si vous exécutez ce fichier en une seule fois avec psql,
-- la commande CREATE DATABASE doit être lancée séparément
-- (elle ne peut pas être dans une transaction avec le reste).
-- =========================================================

CREATE DATABASE gestion_clients_dettes
WITH
    ENCODING 'UTF8' LC_COLLATE = 'fr_FR.UTF-8' LC_CTYPE = 'fr_FR.UTF-8' TEMPLATE = template0;

-- Connectez-vous ensuite à la base avant de continuer :
--   \c gestion_clients_dettes

-- =========================================================
-- ÉTAPE 2 : Création des tables
-- =========================================================

-- Table des utilisateurs (admin ET client, distingués par le rôle)
CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL, -- hash bcrypt (password_hash)
    role VARCHAR(10) NOT NULL DEFAULT 'client',
    photo_profil VARCHAR(255), -- chemin relatif vers public/uploads/clients/
    statut VARCHAR(20) NOT NULL DEFAULT 'nouveau', -- pertinent uniquement pour role = 'client'
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT chk_role CHECK (role IN ('admin', 'client')),
    CONSTRAINT chk_statut CHECK (
        statut IN (
            'nouveau',
            'solvable',
            'non_solvable'
        )
    )
);

-- Index pour accélérer la recherche par nom (filtre admin) et le login par email
CREATE INDEX idx_utilisateurs_nom ON utilisateurs (nom, prenom);

CREATE INDEX idx_utilisateurs_role ON utilisateurs (role);

CREATE INDEX idx_utilisateurs_statut ON utilisateurs (statut);

-- Table des dettes, liées à un client (utilisateur avec role = 'client')
CREATE TABLE dettes (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
    libelle VARCHAR(150) NOT NULL,
    montant NUMERIC(12, 2) NOT NULL CHECK (montant >= 0),
    date_creation DATE NOT NULL DEFAULT CURRENT_DATE,
    date_echeance DATE,
    statut VARCHAR(15) NOT NULL DEFAULT 'non_solde',
    CONSTRAINT chk_dette_statut CHECK (
        statut IN ('solde', 'non_solde')
    )
);

CREATE INDEX idx_dettes_client ON dettes (client_id);

-- =========================================================
-- ÉTAPE 3 : Données préenregistrées
-- Mots de passe en clair (à titre indicatif, jamais stockés ainsi) :
--   admin  -> admin123
--   client -> client123
-- Les hash ci-dessous sont de vrais hash bcrypt valides,
-- vérifiables avec password_verify() en PHP.
-- =========================================================

INSERT INTO
    utilisateurs (
        nom,
        prenom,
        telephone,
        email,
        mot_de_passe,
        role,
        photo_profil,
        statut
    )
VALUES (
        'Ndiaye',
        'Fatou',
        '771234567',
        'admin@gcd.sn',
        '$2b$10$BdRMjudJAHiUSzZVrEwjaeTCKDPDRwbTjXXCrj6Tc1pth55G0NaYa',
        'admin',
        NULL,
        'nouveau'
    ),
    (
        'Diop',
        'Moussa',
        '775551122',
        'moussa.diop@example.com',
        '$2b$10$slHzZw7Q144V6XyO.4Q3ROnaAQvnS0G2640Co8NzjqeRm6DBP4GdW',
        'client',
        NULL,
        'solvable'
    ),
    (
        'Fall',
        'Aissatou',
        '776663344',
        'aissatou.fall@example.com',
        '$2b$10$slHzZw7Q144V6XyO.4Q3ROnaAQvnS0G2640Co8NzjqeRm6DBP4GdW',
        'client',
        NULL,
        'non_solvable'
    ),
    (
        'Sarr',
        'Ibrahima',
        '779998877',
        'ibrahima.sarr@example.com',
        '$2b$10$slHzZw7Q144V6XyO.4Q3ROnaAQvnS0G2640Co8NzjqeRm6DBP4GdW',
        'client',
        NULL,
        'nouveau'
    ),
    (
        'Ba',
        'Awa',
        '771112233',
        'awa.ba@example.com',
        '$2b$10$slHzZw7Q144V6XyO.4Q3ROnaAQvnS0G2640Co8NzjqeRm6DBP4GdW',
        'client',
        NULL,
        'solvable'
    ),
    (
        'Cissé',
        'Ousmane',
        '774445566',
        'ousmane.cisse@example.com',
        '$2b$10$slHzZw7Q144V6XyO.4Q3ROnaAQvnS0G2640Co8NzjqeRm6DBP4GdW',
        'client',
        NULL,
        'non_solvable'
    ),
    (
        'Sy',
        'Mariama',
        '778887766',
        'mariama.sy@example.com',
        '$2b$10$slHzZw7Q144V6XyO.4Q3ROnaAQvnS0G2640Co8NzjqeRm6DBP4GdW',
        'client',
        NULL,
        'nouveau'
    );

INSERT INTO
    dettes (
        client_id,
        libelle,
        montant,
        date_creation,
        date_echeance,
        statut
    )
VALUES (
        2,
        'Achat marchandise - lot A',
        150000.00,
        '2026-03-10',
        '2026-06-10',
        'solde'
    ),
    (
        2,
        'Avance sur commande',
        75000.00,
        '2026-05-01',
        '2026-08-01',
        'non_solde'
    ),
    (
        3,
        'Prêt matériel',
        320000.00,
        '2026-02-15',
        '2026-05-15',
        'non_solde'
    ),
    (
        5,
        'Achat marchandise - lot B',
        98000.00,
        '2026-04-20',
        '2026-07-20',
        'solde'
    ),
    (
        6,
        'Facture impayée n°112',
        210000.00,
        '2026-01-05',
        '2026-04-05',
        'non_solde'
    );