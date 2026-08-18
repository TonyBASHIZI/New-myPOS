<?php 

$errors = [];

$id = $_GET['id'] ?? null;
$amount = $_GET['montant_a_payer'] ?? null;
$product =$_get['productname'] ?? null;

$voir = new Voirboss();

// $row = $sale->first(['id'=>$id]);

if($_SERVER['REQUEST_METHOD'] == "POST")
{

	$errors = $voir->validate($_POST);
	if(empty($errors)){
		
	
		
		$voir->insert($_POST);

		redirect('admin&tab=sales');
	}


}


require views_path('sales/voir-boss.new');
