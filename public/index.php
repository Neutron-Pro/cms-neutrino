<?php
/*
 * Ajout des dépendances de composer.
 */
require_once __DIR__.'/../vendor/autoload.php';

/*
 * Ouverture de la session utilisateur.
 */
session_start();

/*
 * Si le Ficher de configuration n'existe pas alors on lance l'installation du CMS.
 */
if (!file_exists(__DIR__.'/../config/config.json')) {
    (new \Neutrino\Installer\InstallerController())->install();
    die;
}
