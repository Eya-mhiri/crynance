<?php
// Configuration de l'application
session_start();

// Constantes de base
define('BASE_URL', 'http://localhost/crypto_app/');
define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'crypto_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Autoloader des classes
spl_autoload_register(function($class) {
    require ROOT_PATH . '/models/' . $class . '.php';
});

// Initialisation de la connexion DB
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur DB: " . $e->getMessage());
}

// Fonctions globales
function redirect($url) {
    header("Location: " . BASE_URL . ltrim($url, '/'));
    exit;
}
?>
