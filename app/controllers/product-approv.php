<?php 

$errors = [];

$id = $_GET['id'] ?? null;

$product = new Approvisionnement();
$products = new Product();

$row = $products->first(['id'=>$id]);

if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
{
	$data= $_POST['qty_appro'];
	
	if(empty($errors)){

		$product->insert($_POST);
		$product->updates($id,$data);

	

		redirect('admin&tab=approvisionnement');
	}


}


require views_path('products/product-approv');

