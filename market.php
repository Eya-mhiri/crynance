<?php
// En HAUT du fichier
require_once __DIR__ . '/../includes/config.php'; // Configuration DB
require_once __DIR__ . '/../includes/header.php'; // Header HTML
?>
<?php
// 1. Configuration de la page
$pageTitle = "Marchés Cryptos";
$pageCSS = "markets";
$pageJS = "markets";

// 2. Vérification de l'authentification
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit;
}

// 3. Inclusion du header
include __DIR__ . '/../includes/header.php';
?>

<!-- 4. Contenu principal -->
<main class="markets-page">
    <section class="market-header">
        <h1><i class="fas fa-chart-line"></i> Marchés en Temps Réel</h1>
        
        <!-- Barre de recherche -->
        <div class="search-bar">
            <input type="text" id="crypto-search" placeholder="Rechercher une crypto...">
            <button id="refresh-data"><i class="fas fa-sync-alt"></i></button>
        </div>
    </section>

    <!-- Tableau des cryptos -->
    <div class="crypto-table-container">
        <table id="crypto-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>24h</th>
                    <th>Capitalisation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rempli dynamiquement par JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Liens contextuels -->
    <div class="market-actions">
        <a href="/pages/trade.php" class="btn btn-primary">
            <i class="fas fa-exchange-alt"></i> Accéder au Trading
        </a>
        <a href="/pages/portfolio.php" class="btn btn-outline">
            <i class="fas fa-wallet"></i> Voir mon Portfolio
        </a>
    </div>
</main>

<?php
// 5. Inclusion du footer
include __DIR__ . '/../includes/footer.php';
?>
