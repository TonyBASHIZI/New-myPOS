<?php 
// Fichier : ABSPATH/app/views/partials/header.view.php

// Rendre la variable $controller accessible dans cette portée.
// C'est nécessaire car $controller est défini dans $GLOBALS.
global $controller; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Remplacez 'APP_NAME' si nécessaire par la constante que vous utilisez -->
    <title><?= (defined('APP_NAME') ? esc(APP_NAME) : 'APP Name') ?></title> 

    <!-- Si ces assets sont à la racine, les chemins sont corrects. -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>

    <?php 
        // Liste des contrôleurs pour lesquels la navigation n'est PAS affichée
        $no_nav = ["login", "denied"]; // J'ajoute "denied" au cas où
    ?>

    <?php if(!in_array($controller, $no_nav)):?>
        <!-- CORRECTION CRITIQUE : Utiliser la fonction globale view() pour inclure la vue partielle -->
        <?php view('partials/nav'); ?>
    <?php endif;?>

    <div class="container-fluid" style="min-width: 350px;">

    <!-- Le reste du contenu de la page sera chargé ici -->