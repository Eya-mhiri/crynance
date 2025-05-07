<?php
// En HAUT du fichier
require_once __DIR__ . '/../includes/config.php'; // Configuration DB
require_once __DIR__ . '/../includes/header.php'; // Header HTML
?>
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/main.php';

try {
  $action = $_GET['action'] ?? '';
  
  switch ($action) {
    case 'prices':
      // En production, se connecter à CoinGecko/Binance
      $prices = [
        'BTC' => ['price' => rand(45000, 55000), 'change' => rand(-500, 500) / 100],
        'ETH' => ['price' => rand(2500, 3500), 'change' => rand(-500, 500) / 100]
      ];
      echo json_encode($prices);
      break;
      
    default:
      throw new Exception('Action non valide');
  }
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['error' => $e->getMessage()]);
}
