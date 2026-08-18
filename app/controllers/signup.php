<?php 

$errors = [];

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$user = new User();

	$_POST['role'] = "user";
	$_POST['date'] = date("Y-m-d H:i:s");
	
	$errors = $user->validate($_POST);
	if(empty($errors)){
		
		// LIGNE MODIFIÉE : Le hachage est supprimé, le mot de passe reste en texte brut
		// $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
		
		$user->insert($_POST,'users');

		redirect('admin&tab=users');
	}
}
	
if(Auth::access('admin')){
	require views_path('auth/signup');
}else{
	Auth::setMessage("Only admins can create users");
	require views_path('auth/denied');
}
