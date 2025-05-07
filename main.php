<?php
// En HAUT du fichier
require_once __DIR__ . '/../includes/config.php'; // Configuration DB
require_once __DIR__ . '/../includes/header.php'; // Header HTML
?>
<?php
/**
 * Fonctions utilitaires globales
 */

// Chargement automatique des classes
spl_autoload_register(function ($class) {
  require_once __DIR__ . "/../models/{$class}.php";
});

// Sécurité : Protection contre XSS
function sanitize($data) {
  if (is_array($data)) {
    return array_map('sanitize', $data);
  }
  return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validation des données
function validate(array $data, array $rules) {
  $errors = [];
  
  foreach ($rules as $field => $validations) {
    foreach (explode('|', $validations) as $rule) {
      $value = $data[$field] ?? null;
      
      if ($rule === 'required' && empty($value)) {
        $errors[$field][] = "Le champ $field est requis";
      }
      
      if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $errors[$field][] = "Email invalide";
      }
    }
  }
  
  return $errors;
}

// Redirection avec message flash
function redirect($url, $message = null, $type = 'success') {
  if ($message) {
    $_SESSION['flash'] = compact('message', 'type');
  }
  header("Location: $url");
  exit;
}

// Récupération des messages flash
function getFlash() {
  if (empty($_SESSION['flash'])) return null;
  
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
  return $flash;
}
