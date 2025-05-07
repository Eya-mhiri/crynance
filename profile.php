<?php
// En HAUT du fichier
require_once __DIR__ . '/../includes/config.php'; // Configuration DB
require_once __DIR__ . '/../includes/header.php'; // Header HTML
?>
<?php
// 1. Configuration de la page
$pageTitle = "Mon Profil";
$pageCSS = "profile";
$pageJS = "profile";

// 2. Sécurité et sessions
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit;
}

// 3. Récupération des données utilisateur
$user = User::getCurrentUser(); // Supposant que vous avez une classe User

// 4. Inclusion du header
include __DIR__ . '/../includes/header.php';
?>

<!-- 5. Contenu principal -->
<main class="profile-container">
    <section class="profile-header">
        <div class="avatar">
            <img src="/assets/images/avatars/<?= htmlspecialchars($user->id) ?>.jpg" alt="Avatar">
            <button id="change-avatar"><i class="fas fa-camera"></i></button>
        </div>
        
        <h1><?= htmlspecialchars($user->username) ?></h1>
        <p class="email"><?= htmlspecialchars($user->email) ?></p>
    </section>

    <!-- Navigation entre sections -->
    <nav class="profile-tabs">
        <a href="?section=infos" class="<?= !isset($_GET['section']) || $_GET['section'] === 'infos' ? 'active' : '' ?>">
            <i class="fas fa-user"></i> Informations
        </a>
        <a href="?section=security" class="<?= ($_GET['section'] ?? '') === 'security' ? 'active' : '' ?>">
            <i class="fas fa-shield-alt"></i> Sécurité
        </a>
        <a href="?section=activity" class="<?= ($_GET['section'] ?? '') === 'activity' ? 'active' : '' ?>">
            <i class="fas fa-history"></i> Activité
        </a>
    </nav>

    <!-- Sections dynamiques -->
    <div class="profile-content">
        <?php
        $section = $_GET['section'] ?? 'infos';
        switch ($section) {
            case 'security':
                include __DIR__ . '/../includes/profile_security.php';
                break;
            case 'activity':
                include __DIR__ . '/../includes/profile_activity.php';
                break;
            default:
                include __DIR__ . '/../includes/profile_infos.php';
        }
        ?>
    </div>

    <!-- Liens rapides -->
    <div class="profile-actions">
        <a href="/pages/dashboard.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
        <a href="/auth/logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</main>

<?php
// 6. Inclusion du footer
include __DIR__ . '/../includes/footer.php';
?>

