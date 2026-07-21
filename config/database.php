<?php
/**
 * Paramètres de connexion à la base PostgreSQL.
 * Adapter host/port/nom de la base selon l'environnement.
 */
return [
    'driver'   => 'pgsql',
    'host'     => '127.0.0.1',
    'port'     => '5432',
    'db_name'  => 'gestion_clients_dettes',
    'username' => 'postgres',
    'password' => 'seydinathiam05',
    'charset'  => 'utf8',
];