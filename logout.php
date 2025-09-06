<?php
session_start(); // Démarrer la session

// Supprimer toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page de login
header("Location: login.php");
exit;
