<?php
// En HAUT du fichier
require_once __DIR__ . '/../includes/config.php'; // Configuration DB
require_once __DIR__ . '/../includes/header.php'; // Header HTML
?>
<?php
class User {
  public $id;
  public $email;
  public $password_hash;
  public $created_at;

  public static function find($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchObject(__CLASS__);
  }

  public static function authenticate($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetchObject(__CLASS__);
    
    if (!$user || !password_verify($password, $user->password_hash)) {
      throw new Exception("Identifiants invalides");
    }
    
    return $user;
  }

  public static function register($email, $password) {
    global $pdo;
    
    // Validation supplémentaire...
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("
      INSERT INTO users (email, password_hash) 
      VALUES (?, ?)
    ");
    $stmt->execute([$email, $password_hash]);
    
    return self::find($pdo->lastInsertId());
  }
}
