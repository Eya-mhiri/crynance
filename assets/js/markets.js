
class MarketsPage {
  static init() {
    this.loadMarketData();
    this.setupEventListeners();
    this.initDataTable();
  }

  static async loadMarketData() {
    try {
      // Show loading state
      document.getElementById('market-loading').classList.remove('d-none');
      
      // Simulated API call (replace with real fetch)
      const data = await this.fetchMarketData();
      
      // Populate table
      this.renderMarketData(data);
      
      // Update last refresh time
      document.getElementById('last-refresh').textContent = new Date().toLocaleTimeString();
    } catch (error) {
      console.error('Failed to load market data:', error);
      Notifier.show('Failed to load market data', 'error');
    } finally {
      document.getElementById('market-loading').classList.add('d-none');
    }
  }

  static async fetchMarketData() {
    // Simulated data - replace with actual API call
    return [
      { symbol: 'BTC', name: 'Bitcoin', price: 50234.56, change24h: 2.34, volume: 28400000000, marketCap: 950000000000 },
      { symbol: 'ETH', name: 'Ethereum', price: 3012.78, change24h: -1.23, volume: 15000000000, marketCap: 350000000000 },
      // ... more coins
    ];
  }

  static renderMarketData(data) {
    const tbody = document.querySelector('#crypto-table tbody');
    tbody.innerHTML = '';
    
    data.forEach(crypto => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>
          <div class="crypto-name-cell">
            <img src="../assets/images/logos/${crypto.symbol.toLowerCase()}.png" alt="${crypto.name}" class="crypto-logo">
            <div>
              <div class="font-weight-600">${crypto.name}</div>
              <div class="text-muted">${crypto.symbol}</div>
            </div>
          </div>
        </td>
        <td class="text-right">$${crypto.price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        <td class="text-right">
          <span class="price-change ${crypto.change24h >= 0 ? 'positive' : 'negative'}">
            ${crypto.change24h >= 0 ? '+' : ''}${crypto.change24h.toFixed(2)}%
          </span>
        </td>
        <td class="text-right">$${(crypto.volume / 1000000000).toFixed(2)}B</td>
        <td class="text-right">$${(crypto.marketCap / 1000000000).toFixed(2)}B</td>
        <td class="text-right">
          <button class="btn btn-sm btn-outline-primary trade-btn" data-symbol="${crypto.symbol}">
            Trade
          </button>
        </td>
      `;
      tbody.appendChild(row);
    });
  }

  static initDataTable() {
    // Configuration de base pour DataTables
    $('#crypto-table').DataTable({
      responsive: true,
      order: [[4, 'desc']], // Tri par market cap par défaut
      language: {
        search: '_INPUT_',
        searchPlaceholder: 'Rechercher...',
        paginate: {
          previous: '‹',
          next: '›'
        }
      },
      dom: '<"top"<"row"<"col-md-6"f><"col-md-6"l>>>rt<"bottom"<"row"<"col-md-6"i><"col-md-6"p>>>'
    });
  }

  static setupEventListeners() {
    // Refresh button
    document.getElementById('refresh-market').addEventListener('click', () => {
      this.loadMarketData();
      Notifier.show('Market data refreshed', 'success');
    });
    
    // Trade buttons
    document.addEventListener('click', (e) => {
      if (e.target.closest('.trade-btn')) {
        const symbol = e.target.closest('.trade-btn').dataset.symbol;
        window.location.href = `../pages/trade.php?symbol=${symbol}`;
      }
    });
    
    // Filter form
    document.getElementById('market-filter-form').addEventListener('submit', (e) => {
      e.preventDefault();
      this.applyFilters();
    });
  }

  static applyFilters() {
    const form = document.getElementById('market-filter-form');
    const formData = new FormData(form);
    
    // Ici vous filtreriez les données en fonction des critères
    console.log('Applying filters:', Object.fromEntries(formData.entries()));
    Notifier.show('Filters applied', 'success');
  }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => MarketsPage.init());
