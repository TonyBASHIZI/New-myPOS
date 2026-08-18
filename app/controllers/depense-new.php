<?php 

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	// var_dump($_POST);die();
	
	$depense = new Depense();
	$errors = $depense->validate($_POST);
	$_POST['user_id'] = auth("id");

	if(empty($errors))
	{
		$depense->insert($_POST);

		if ($depense) {

		   echo json_encode(['status' => 'success', 'message' => 'Insertion réussie']);
		}else{
			// Échec
    		echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'insertion']);
		}

		 
	}

}


require views_path('products/depense-new');

