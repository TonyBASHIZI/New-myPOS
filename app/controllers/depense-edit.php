<?php 

$errors = [];
// 1. CHANGER LA CLASSE CIBLE : Utiliser la classe Depense (ou Expense)
$depense = new Depense(); // Assurez-vous que cette classe existe et a les méthodes nécessaires

// Récupérer l'ID de la dépense depuis l'URL
$id = $_GET['id_depense'] ?? null;
// Récupérer les données actuelles de la dépense
$row = $depense->first(['id_depense' => $id]); 

// Logique de redirection (Garde la référence de la page précédente, sans grande modification)
if(!empty($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "depense-edit"))
{
    $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // *** 2. SUPPRIMER LA LOGIQUE UTILISATEUR : Suppressions des rôles, mots de passe, et images ***
    
    // Le tableau $_POST doit contenir uniquement 'motif' et 'montant' (et potentiellement d'autres champs de dépense)
    
    // Validation des données (vérifie 'motif' et 'montant')
    // Assurez-vous que la méthode validate dans la classe Depense gère ces deux champs
    $errors = $depense->validate($_POST, $id); 

    if(empty($errors))
    {
        // 3. MISE À JOUR : Mise à jour de la dépense
        $depense->update($id, $_POST);

        // Rediriger vers la page d'édition ou une page de confirmation
        // Note: 'edit-user&id=$id' est remplacé par 'depense-edit&id=$id'
        redirect("depense-edit&id=$id"); 
    }
}
    
	
if(Auth::access('admin') || ($row && $row['id'] == Auth::get('id'))){
	require views_path('products/depense-edit');
}else{

	Auth::setMessage("Only admins can create users");
	require views_path('auth/denied');
}

