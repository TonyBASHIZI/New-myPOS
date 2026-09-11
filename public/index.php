<?php

session_start();

define("ABSPATH", __DIR__);

require "../app/core/init.php";

$controller = $_GET['pg'] ?? "home";
$controller = strtolower($controller);

// Pages that don't require login
$public_pages = ['login', 'register'];

if(!in_array($controller, $public_pages))
{
    require_login();
}

if(file_exists("../app/controllers/".$controller . ".php"))
{
	require "../app/controllers/".$controller . ".php";
}else{
	echo "controller not found";
}