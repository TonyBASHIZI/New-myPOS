<?php 

$errors = [];

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $user = new User();
    
    // 1. Récupérer l'utilisateur par email.
    $row = $user->where(['email' => $_POST['email']]);
    
    if($row)
    {
        // Accès au premier résultat, qui est un TABLEAU associatif : $row[0]
        $userData = $row[0]; 

        // 2. VÉRIFICATION NON SÉCURISÉE (Mot de passe en clair)
        // Comparaison directe du mot de passe posté avec le mot de passe en BDD
        if($_POST['password'] === $userData['password']) 
        {
            // Mot de passe correct
            authenticate($userData);
            redirect('home');
        } 
        else 
        {
            // Mot de passe incorrect
            $errors['password'] = "Mot de passe incorrect.";
        }
    }
    else
    {
        // Email non trouvé
        $errors['email'] = "Cet email n'existe pas.";
    }
}

require views_path('auth/login');