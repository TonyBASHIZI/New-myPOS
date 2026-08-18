<?php 

// Utiliser les chemins ABSOLUS définis dans public/index.php
// ABSPATH = /htdocs/
require ABSPATH . "/app/core/config.php";
require ABSPATH . "/app/core/functions.php";
require ABSPATH . "/app/core/database.php";
require ABSPATH . "/app/core/model.php";


// --- FONCTIONS GLOBALES (DÉFINIES ICI POUR ÊTRE DISPONIBLES PARTOUT) ---

/**
 * Charge un fichier de vue à partir du chemin global VIEW_PATH.
 * VIEW_PATH est défini dans public/index.php.
 * @param string $name Le nom de la vue (ex: 'auth/login', 'partials/header').
 * @param array $data Les données à passer à la vue.
 */
function view($name, $data = [])
{
    // Rendre les variables du tableau $data disponibles dans la vue
    extract($data); 

    // Construction du chemin de vue ABSOLU.
    // Utilise la constante globale VIEW_PATH
    $filename = VIEW_PATH . $name . ".view.php"; // Utilise l'extension .view.php

    if(file_exists($filename))
    {
        require $filename; 
    } else {
        echo "Error: view '" . htmlspecialchars($name) . "' not found at path: " . htmlspecialchars($filename);
    }
}


// --- AUTOLOADER ---

spl_autoload_register('my_function');

function my_function($classname)
{
    // Utiliser ABSPATH pour que l'autoloader fonctionne depuis n'importe quel répertoire
    $filename = ABSPATH . "/app/models/".ucfirst($classname) . ".php";
    
    if(file_exists($filename)){
        require $filename;
    }
}