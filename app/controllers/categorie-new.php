<?php 

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	var_dump($_POST);die();
	
	$categorie = new Categorie();
	$errors = $categorie->validate($_POST);

	if(empty($errors))
	{
		$categorie->insert($_POST);

		if ($categorie) {

		   echo json_encode(['status' => 'success', 'message' => 'Insertion réussie']);
		}else{
			// Échec
    		echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'insertion']);
		}

		 
	}

}


require views_path('products/categorie-new');

