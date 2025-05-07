<?php
require_once __DIR__ . '/../includes/main.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = sanitize($_POST);
  $errors = validate($data, [
    'email' => 'required|email',
    'password' => 'required'
  ]);

  if (empty($errors)) {
    try {
      $user = User::authenticate($data['email'], $data['password']);
      
      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email;
      
      redirect('../dashboard.php');
    } catch (Exception $e) {
      $errors['general'] = $e->getMessage();
    }
  }
}

include __DIR__ . '/../includes/auth_header.php';
?>

<div class="auth-container">
  <h2>Connexion</h2>
  
  <?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger"><?= $errors['general'] ?></div>
  <?php endif; ?>
  
  <form method="POST">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
      <?php if (isset($errors['email'])): ?>
        <small class="text-danger"><?= $errors['email'][0] ?></small>
      <?php endif; ?>
    </div>
    
    <div class="form-group">
      <label>Mot de passe</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
  </form>
  
  <div class="auth-footer">
    <p>Pas de compte ? <a href="register.php">S'inscrire</a></p>
    <p><a href="forgot_password.php">Mot de passe oublié ?</a></p>
  </div>
</div>

<?php include __DIR__ . '/../includes/auth_footer.php'; ?>
