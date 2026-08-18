<?php
require_once 'mailer.php';


$ok = envoyerMail("sergemubakire@gmail.com", "Test MAIL", "Ceci est un mail de production.");
echo $ok ? "Mail envoyé." : "Erreur à l'envoi.";