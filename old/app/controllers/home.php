<?php 
// Fichier : ABSPATH/app/controllers/home.php

// Suppression de la définition de VIEW_PATH pour forcer le chemin dans la fonction view()

// --- LOGIQUE DU CONTRÔLEUR ---

// Cette fonction simplifiée est la seule chose dont vous avez besoin pour charger la vue
// function view($name, $data = [])
// {
//     // Rendre les variables du tableau $data disponibles dans la vue
//     extract($data); 

//     // CORRECTION CRITIQUE: Changer l'extension de '.php' à '.view.php'
//     // Construction du chemin de vue ABSOLU en utilisant ABSPATH, 
//     // garantissant que le chemin est correct: /htdocs/app/views/auth/denied.view.php
//     $filename = ABSPATH . "/app/views/" . $name . ".view.php";

//     // VÉRIFICATION CRITIQUE
//     if(file_exists($filename))
//     {
//         // LIGNE 10: Chargement du fichier de vue
//         require $filename; 
//     } else {
//         // Ajout d'un message de débogage plus clair pour voir quel chemin est réellement utilisé.
//         echo "Error: view '" . htmlspecialchars($name) . "' not found at path: " . htmlspecialchars($filename);
//     }
// }

// --- LOGIQUE DE L'APPLICATION (Exemple) ---

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    // Si l'utilisateur n'est pas connecté, charge la vue de connexion
    view('auth/denied'); 
} else {
    // Si l'utilisateur est connecté, charge la vue d'accueil
    // ... autre logique ...
    view('home'); 
}