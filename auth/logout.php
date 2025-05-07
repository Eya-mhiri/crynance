<?php
require_once __DIR__ . '/../includes/config.php';

// Destruction de la session
$_SESSION = [];
session_destroy();

// Redirection vers la page de login
redirect('auth/login.php');
?>
