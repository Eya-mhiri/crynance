<?php
require_once __DIR__ . '/../includes/main.php';
require_once __DIR__ . '/../includes/auth.php';

// Vérification de l'authentification
if (!isLoggedIn()) {
  redirect('../auth/login.php');
}

// Récupération des données utilisateur
$user = User::find($_SESSION['user_id']);
$wallet = Wallet::getByUser($user->id);
$transactions = Transaction::recent($user->id, 5);

// Données pour les graphiques
$portfolioData = [
  'BTC' => ['value' => $wallet->btc_balance * 50000, 'color' => '#f7931a'],
  'ETH' => ['value' => $wallet->eth_balance * 3000, 'color' => '#627eea'],
  'USD' => ['value' => $wallet->usd_balance, 'color' => '#4e5d6c']
];

// Rendue de la vue
include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard-container">
  <div class="row">
    <!-- Carte Portfolio -->
    <div class="col-md-6">
      <div class="card">
        <h3>Votre Portfolio</h3>
        <div class="portfolio-chart-container">
          <canvas id="portfolio-chart"></canvas>
        </div>
      </div>
    </div>
    
    <!-- Marché en temps réel -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3>Marché</h3>
          <button id="refresh-data" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-sync-alt"></i>
          </button>
        </div>
        <div class="market-data">
          <div class="crypto-price">
            <span class="crypto-name">
              <img src="../assets/images/btc.png" alt="BTC"> Bitcoin
            </span>
            <span id="btc-price">$50,234.56</span>
            <span id="btc-change" class="positive">+2.34%</span>
          </div>
          <!-- Plus de cryptos... -->
        </div>
      </div>
    </div>
  </div>
  
  <!-- Dernières transactions -->
  <div class="card mt-4">
    <h3>Activité récente</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Montant</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transactions as $tx): ?>
        <tr>
          <td><?= date('d/m/Y H:i', strtotime($tx->created_at)) ?></td>
          <td><?= $tx->type === 'buy' ? 'Achat' : 'Vente' ?></td>
          <td><?= $tx->amount ?> <?= $tx->crypto_symbol ?></td>
          <td><span class="badge bg-success">Complété</span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php 
include __DIR__ . '/../includes/footer.php';
?>
