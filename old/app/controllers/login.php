<?php 
// Fichier : ABSPATH/app/controllers/login.php
view('partials/header');

// Rendre $controller accessible aux vues (header, nav).
global $controller; 
$errors = []; // Initialiser le tableau d'erreurs

// --- 1. LOGIQUE DE TRAITEMENT DU FORMULAIRE POST ---
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $user = new User();
    
    // Récupérer le premier utilisateur correspondant à l'email (where retourne un tableau de lignes)
    $rows = $user->where(['email' => $_POST['email']]); 

    if($rows)
    {
        $row = $rows[0]; // On prend le premier utilisateur trouvé
        
        // CORRECTION CRITIQUE: Utiliser password_verify() pour la sécurité.
        // Assurez-vous que les mots de passe dans la base sont hachés avec password_hash().
        if(password_verify($_POST['password'], $row->password))
        {
            // Authentification réussie
            authenticate($row); // Supposons que cette fonction démarre la session
            redirect('home');
        } else {
            // Mot de passe incorrect
            $errors['password'] = "Mot de passe incorrect.";
        }
    } else {
        // Email non trouvé
        $errors['email'] = "Adresse e-mail inconnue.";
    }
}

// --- 2. AFFICHAGE DE LA VUE DE CONNEXION ---

// CORRECTION CRITIQUE: Utiliser la fonction globale view() pour inclure la vue.
// Le chemin complet recherché est VIEW_PATH . 'auth/login.view.php'
view('auth/login', [
    'page_name' => $controller,
    'errors' => $errors // Passer le tableau d'erreurs à la vue
]);