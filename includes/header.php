<?php
// Vérification de la session pour les pages protégées
$protected_pages = ['dashboard', 'portfolio', 'trade'];
$current_page = basename($_SERVER['PHP_SELF'], '.php');

if (in_array($current_page, $protected_pages) && !isset($_SESSION['user_id'])) {
    redirect('auth/login.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Crypto App' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <?php if(isset($page_css)): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/<?= $page_css ?>.css">
    <?php endif; ?>
</head>
<body>
    <header class="main-header">
        <nav>
            <a href="<?= BASE_URL ?>" class="logo">
                <img src="<?= BASE_URL ?>assets/images/logo.png" alt="Logo">
            </a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="nav-links">
                    <a href="<?= BASE_URL ?>pages/dashboard.php" 
                       class="<?= $current_page === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>pages/portfolio.php"
                       class="<?= $current_page === 'portfolio' ? 'active' : '' ?>">
                        <i class="fas fa-wallet"></i> Portfolio
                    </a>
                </div>
                
                <div class="user-menu">
                    <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="<?= BASE_URL ?>auth/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </header>
    
    <main class="container">
