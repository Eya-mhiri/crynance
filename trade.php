<?php
// En HAUT du fichier
require_once __DIR__ . '/../includes/config.php'; // Configuration DB
require_once __DIR__ . '/../includes/header.php'; // Header HTML
?>
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Verify user is authenticated
if (!isAuthenticated()) {
    header("Location: ../auth/login.php");
    exit;
}

// Get user wallet data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallet = $stmt->fetch();

// Get recent transactions
$stmt = $pdo->prepare("
    SELECT * FROM transactions 
    WHERE user_id = ? 
    ORDER BY transaction_date DESC 
    LIMIT 5
");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

// Available trading pairs
$tradingPairs = [
    'BTC' => ['name' => 'Bitcoin', 'price' => 50234.56, 'change' => 2.34],
    'ETH' => ['name' => 'Ethereum', 'price' => 3012.78, 'change' => -1.23],
    'SOL' => ['name' => 'Solana', 'price' => 152.45, 'change' => 5.67],
    'ADA' => ['name' => 'Cardano', 'price' => 1.25, 'change' => 0.45],
    'DOT' => ['name' => 'Polkadot', 'price' => 28.90, 'change' => -0.78]
];

// Process trade if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $crypto = $_POST['crypto'];
    $amount = (float)$_POST['amount'];
    
    // Validate inputs
    if (!array_key_exists($crypto, $tradingPairs)) {
        $error = "Invalid cryptocurrency selected.";
    } elseif ($amount <= 0) {
        $error = "Amount must be greater than zero.";
    } else {
        $price = $tradingPairs[$crypto]['price'];
        $total = $amount * $price;
        $crypto_balance_field = strtolower($crypto) . '_balance';
        
        try {
            $pdo->beginTransaction();
            
            if ($action === 'buy') {
                if ($wallet['usd_balance'] < $total) {
                    throw new Exception("Insufficient USD balance.");
                }
                
                // Update wallet
                $stmt = $pdo->prepare("
                    UPDATE wallets 
                    SET usd_balance = usd_balance - :total, 
                        $crypto_balance_field = $crypto_balance_field + :amount 
                    WHERE user_id = :user_id
                ");
                $stmt->execute([
                    'total' => $total,
                    'amount' => $amount,
                    'user_id' => $user_id
                ]);
            } else {
                if ($wallet[$crypto_balance_field] < $amount) {
                    throw new Exception("Insufficient $crypto balance.");
                }
                
                // Update wallet
                $stmt = $pdo->prepare("
                    UPDATE wallets 
                    SET usd_balance = usd_balance + :total, 
                        $crypto_balance_field = $crypto_balance_field - :amount 
                    WHERE user_id = :user_id
                ");
                $stmt->execute([
                    'total' => $total,
                    'amount' => $amount,
                    'user_id' => $user_id
                ]);
            }
            
            // Record transaction
            $stmt = $pdo->prepare("
                INSERT INTO transactions 
                (user_id, crypto_symbol, transaction_type, amount, price) 
                VALUES (:user_id, :crypto, :action, :amount, :price)
            ");
            $stmt->execute([
                'user_id' => $user_id,
                'crypto' => $crypto,
                'action' => $action,
                'amount' => $amount,
                'price' => $price
            ]);
            
            $pdo->commit();
            $success = "Trade executed successfully!";
            
            // Refresh wallet data
            $stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $wallet = $stmt->fetch();
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade | Crypto Platform</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/trade.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container">
        <h1>Trading Platform</h1>
        
        <div id="alerts-container">
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="trading-container">
            <div class="trade-form">
                <form id="trade-form" method="POST">
                    <div class="form-group">
                        <label for="action">Action</label>
                        <select id="action" name="action" class="form-control" required>
                            <option value="buy">Buy</option>
                            <option value="sell">Sell</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="crypto">Cryptocurrency</label>
                        <select id="crypto" name="crypto" class="form-control" required>
                            <?php foreach ($tradingPairs as $symbol => $pair): ?>
                                <option value="<?= $symbol ?>">
                                    <?= $pair['name'] ?> (<?= $symbol ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control" 
                               step="0.00000001" min="0" required>
                    </div>
                    
                    <div class="price-display">
                        <p>Current Price: <span id="current-price">$<?= number_format($tradingPairs['BTC']['price'], 2) ?></span>
                        <span class="<?= $tradingPairs['BTC']['change'] >= 0 ? 'positive' : 'negative' ?>">
                            (<?= $tradingPairs['BTC']['change'] >= 0 ? '+' : '' ?><?= number_format($tradingPairs['BTC']['change'], 2) ?>%)
                        </span></p>
                        <p>Estimated Cost: <span id="estimated-cost">$0.00</span></p>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Execute Trade</button>
                </form>
            </div>
            
            <div class="wallet-summary">
                <h2>Your Wallet</h2>
                <div class="wallet-balance">
                    <h3>USD Balance</h3>
                    <p>$<?= number_format($wallet['usd_balance'], 2) ?></p>
                </div>
                <div class="wallet-balance">
                    <h3>BTC Balance</h3>
                    <p><?= number_format($wallet['btc_balance'], 8) ?></p>
                </div>
                <div class="wallet-balance">
                    <h3>ETH Balance</h3>
                    <p><?= number_format($wallet['eth_balance'], 8) ?></p>
                </div>
                
                <h3 class="mt-3">Recent Transactions</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Crypto</th>
                            <th>Amount</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $tx): ?>
                            <tr>
                                <td class="<?= $tx['transaction_type'] === 'buy' ? 'positive' : 'negative' ?>">
                                    <?= ucfirst($tx['transaction_type']) ?>
                                </td>
                                <td><?= $tx['crypto_symbol'] ?></td>
                                <td><?= number_format($tx['amount'], 8) ?></td>
                                <td>$<?= number_format($tx['price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="4">No transactions yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <h2>Trading Pairs</h2>
            <div id="trading-pairs" class="trading-pairs"></div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/trade.js"></script>
</body>
</html>
    <?php
// En BAS du fichier
require_once __DIR__ . '/../includes/footer.php'; // Footer + JS
?>
