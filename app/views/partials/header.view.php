<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=esc(APP_NAME)?></title>
    <link rel="icon" type="image/png" href="/images/logoPBcars.jpeg">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

	<!-- Export excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>


    <style>
    	@keyframes disappear {
		    0%{opacity: 1; transform: translateY(0px);}
		    100%{opacity: 0; transform: translateY(-20px);}
		}
    </style>   

</head>
<body>

	<?php 
		$no_nav[] = "login";
	?>
	<?php if(!in_array($controller, $no_nav)):?>
		<?php require views_path('partials/nav');?>
	<?php endif;?>

	<div class="container-fluid" style="min-width: 450px;">
