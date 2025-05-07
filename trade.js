assets/js/trade.js
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const cryptoSelect = document.getElementById('crypto');
    const amountInput = document.getElementById('amount');
    const actionSelect = document.getElementById('action');
    const currentPriceEl = document.getElementById('current-price');
    const estimatedCostEl = document.getElementById('estimated-cost');
    const tradingPairsContainer = document.getElementById('trading-pairs');
    const form = document.getElementById('trade-form');
    
    // Trading pairs data
    const tradingPairs = [
        { symbol: 'BTC', name: 'Bitcoin', price: 0, change: 0 },
        { symbol: 'ETH', name: 'Ethereum', price: 0, change: 0 },
        { symbol: 'SOL', name: 'Solana', price: 0, change: 0 },
        { symbol: 'ADA', name: 'Cardano', price: 0, change: 0 },
        { symbol: 'DOT', name: 'Polkadot', price: 0, change: 0 }
    ];
    
    // Current selected pair
    let currentPair = tradingPairs[0];
    
    // Initialize trading pairs
    function initTradingPairs() {
        tradingPairsContainer.innerHTML = '';
        
        tradingPairs.forEach(pair => {
            const pairCard = document.createElement('div');
            pairCard.className = 'pair-card';
            pairCard.innerHTML = `
                <h4>${pair.symbol}</h4>
                <p>${pair.name}</p>
                <div class="pair-price" data-symbol="${pair.symbol}">
                    $<span class="price">0.00</span>
                    <span class="change">0.00%</span>
                </div>
            `;
            
            pairCard.addEventListener('click', () => selectPair(pair));
            tradingPairsContainer.appendChild(pairCard);
        });
        
        // Load initial prices
        fetchMarketData();
    }
    
    // Select trading pair
    function selectPair(pair) {
        currentPair = pair;
        cryptoSelect.value = pair.symbol;
        
        // Update UI
        document.querySelectorAll('.pair-card').forEach(card => {
            card.classList.remove('active');
        });
        event.currentTarget.classList.add('active');
        
        updatePriceDisplay();
        updateEstimate();
    }
    
    // Update price display
    function updatePriceDisplay() {
        currentPriceEl.textContent = `$${currentPair.price.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 8
        })}`;
        
        const changeEl = currentPriceEl.nextElementSibling;
        changeEl.textContent = `${currentPair.change >= 0 ? '+' : ''}${currentPair.change.toFixed(2)}%`;
        changeEl.className = currentPair.change >= 0 ? 'positive' : 'negative';
    }
    
    // Update cost estimate
    function updateEstimate() {
        const amount = parseFloat(amountInput.value) || 0;
        const total = amount * currentPair.price;
        const action = actionSelect.value;
        
        estimatedCostEl.textContent = `$${total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })} ${action === 'buy' ? 'to buy' : 'to receive'}`;
    }
    
    // Fetch market data from API
    async function fetchMarketData() {
        try {
            // In a real app, replace with actual API call
            // const response = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd');
            // const data = await response.json();
            
            // Mock data for demonstration
            const mockData = [
                { symbol: 'btc', current_price: 50234.56, price_change_percentage_24h: 2.34 },
                { symbol: 'eth', current_price: 3012.78, price_change_percentage_24h: -1.23 },
                { symbol: 'sol', current_price: 152.45, price_change_percentage_24h: 5.67 },
                { symbol: 'ada', current_price: 1.25, price_change_percentage_24h: 0.45 },
                { symbol: 'dot', current_price: 28.90, price_change_percentage_24h: -0.78 }
            ];
            
            // Update trading pairs with real data
            tradingPairs.forEach(pair => {
                const coinData = mockData.find(coin => coin.symbol === pair.symbol.toLowerCase());
                if (coinData) {
                    pair.price = coinData.current_price;
                    pair.change = coinData.price_change_percentage_24h;
                    
                    // Update UI
                    const priceEl = document.querySelector(`.pair-price[data-symbol="${pair.symbol}"] .price`);
                    const changeEl = document.querySelector(`.pair-price[data-symbol="${pair.symbol}"] .change`);
                    
                    if (priceEl && changeEl) {
                        priceEl.textContent = coinData.current_price.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 8
                        });
                        
                        changeEl.textContent = `${coinData.price_change_percentage_24h >= 0 ? '+' : ''}${coinData.price_change_percentage_24h.toFixed(2)}%`;
                        changeEl.className = coinData.price_change_percentage_24h >= 0 ? 'positive' : 'negative';
                    }
                }
            });
            
            // Update current pair if needed
            if (currentPair) {
                const updatedPair = tradingPairs.find(p => p.symbol === currentPair.symbol);
                if (updatedPair) {
                    currentPair.price = updatedPair.price;
                    currentPair.change = updatedPair.change;
                    updatePriceDisplay();
                }
            }
        } catch (error) {
            console.error('Error fetching market data:', error);
        }
    }
    
    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const amount = parseFloat(formData.get('amount'));
        
        if (amount <= 0) {
            showAlert('Please enter a valid amount', 'error');
            return;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // In a real app, you would send this to your backend
            // const response = await fetch('/api/trade', {
            //     method: 'POST',
            //     body: formData
            // });
            // const result = await response.json();
            
            // Mock success response
            showAlert(`Trade executed successfully! ${formData.get('action') === 'buy' ? 'Bought' : 'Sold'} ${amount} ${currentPair.symbol} at $${currentPair.price.toFixed(2)}`, 'success');
            
            // Reset form
            form.reset();
            updateEstimate();
        } catch (error) {
            showAlert('Trade failed: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Execute Trade';
        }
    });
    
    // Show alert message
    function showAlert(message, type) {
        const alertEl = document.createElement('div');
        alertEl.className = `alert alert-${type}`;
        alertEl.textContent = message;
        
        const alertsContainer = document.getElementById('alerts-container');
        alertsContainer.prepend(alertEl);
        
        setTimeout(() => {
            alertEl.remove();
        }, 5000);
    }
    
    // Event listeners
    cryptoSelect.addEventListener('change', function() {
        const selectedPair = tradingPairs.find(p => p.symbol === this.value);
        if (selectedPair) {
            selectPair(selectedPair);
        }
    });
    
    amountInput.addEventListener('input', updateEstimate);
    actionSelect.addEventListener('change', updateEstimate);
    
    // Initialize
    initTradingPairs();
    
    // Refresh prices every 30 seconds
    setInterval(fetchMarketData, 30000);
});
