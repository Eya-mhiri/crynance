
<?php
$page_title = "Inscription";
$page_css = "auth";
$page_js = "auth";

require_once __DIR__ . '/../includes/config.php';

if (isset($_SESSION['user_id'])) {
    redirect('pages/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    try {
        $user = User::create($username, $email, $password);
        $_SESSION['user_id'] = $user->id;
        redirect('pages/dashboard.php');
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-container">
    <h1><i class="fas fa-user-plus"></i> Inscription</h1>
    
    <?php if(isset($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" class="auth-form">
        <div class="form-group">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" required>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Mot de passe</label>
            <div class="password-input">
                <input type="password" name="password" required minlength="8">
                <button type="button" class="toggle-password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-check"></i> S'inscrire
        </button>
    </form>
    
    <div class="auth-links">
        Déjà inscrit ? <a href="login.php">Se connecter</a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
