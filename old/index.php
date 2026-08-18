<?php

session_start();

// --- 1. DÉFINITION DES CHEMINS CRITIQUES ---

// ABSPATH: Définit la racine de l'application (/htdocs/).
// Comme ce fichier est à la racine, __DIR__ est le chemin correct.
define("ABSPATH", __DIR__); 

// VIEW_PATH: Définit le chemin absolu vers le dossier des vues. C'est la ligne CRITIQUE.
// Cette ligne doit exister et être exécutée avant d'appeler init.php.
define("VIEW_PATH", ABSPATH . "/app/views/"); 


// --- 2. GESTION DU CONTRÔLEUR ---

// Récupère le nom du contrôleur de l'URL (par défaut 'home')
$controller = $_GET['pg'] ?? "home";
$controller = strtolower($controller);

// Rendre $controller global.
$GLOBALS['controller'] = $controller;


// --- 3. CHARGEMENT DE L'INITIALISATION GLOBALE ---

// Charge init.php APRÈS que VIEW_PATH soit défini.
// NOTE: La référence au dossier '/pos' a été supprimée ici et partout ailleurs.
require_once ABSPATH . "/app/core/init.php";


// --- 4. EXÉCUTION DU CONTRÔLEUR ---

// Construction du chemin absolu du contrôleur
$controller_path = ABSPATH . "/app/controllers/" . $controller . ".php";

if(file_exists($controller_path))
{
    require $controller_path;
} else {
    // Gestion d'une erreur 404
    http_response_code(404);
    echo "Fatal Error: Controller not found. Path tried: " . htmlspecialchars($controller_path);
}