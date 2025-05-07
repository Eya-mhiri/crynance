assets/js/portfolio.js
class PortfolioPage {
  static init() {
    this.loadPortfolioData();
    this.setupEventListeners();
    this.initCharts();
  }

  static async loadPortfolioData() {
    try {
      // Show loading state
      document.getElementById('portfolio-loading').classList.remove('d-none');
      
      // Simulated API call
      const data = await this.fetchPortfolioData();
      
      // Update UI
      this.renderPortfolioSummary(data.summary);
      this.renderAssetsList(data.assets);
    } catch (error) {
      console.error('Failed to load portfolio data:', error);
      Notifier.show('Failed to load portfolio', 'error');
    } finally {
      document.getElementById('portfolio-loading').classList.add('d-none');
    }
  }

  static async fetchPortfolioData() {
    // Simulated data - replace with actual API call
    return {
      summary: {
        totalValue: 12500.34,
        dayChange: 245.67,
        dayChangePercent: 2.01,
        allocation: {
          BTC: 65,
          ETH: 25,
          SOL: 5,
          USD: 5
        }
      },
      assets: [
        { symbol: 'BTC', name: 'Bitcoin', amount: 0.25, value: 12560, price: 50240, change24h: 2.34 },
        { symbol: 'ETH', name: 'Ethereum', amount: 4.2, value: 12650, price: 3012, change24h: -1.23 }
      ]
    };
  }

  static renderPortfolioSummary(data) {
    document.getElementById('portfolio-total').textContent = 
      `$${data.totalValue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    
    const changeElement = document.getElementById('portfolio-change');
    changeElement.textContent = 
      `${data.dayChange >= 0 ? '+' : ''}$${Math.abs(data.dayChange).toFixed(2)} (${data.dayChangePercent.toFixed(2)}%)`;
    changeElement.className = data.dayChange >= 0 ? 'positive' : 'negative';
    
    // Update allocation chart
    this.updateAllocationChart(data.allocation);
  }

  static renderAssetsList(assets) {
    const container = document.getElementById('assets-container');
    container.innerHTML = '';
    
    assets.forEach(asset => {
      const element = document.createElement('div');
      element.className = 'asset-card';
      element.innerHTML = `
        <div class="asset-info">
          <img src="../assets/images/logos/${asset.symbol.toLowerCase()}.png" alt="${asset.name}" class="asset-logo">
          <div>
            <div class="asset-name">${asset.name}</div>
            <div class="asset-symbol">${asset.symbol}</div>
          </div>
        </div>
        <div class="asset-value">
          <div class="asset-amount">${asset.amount} ${asset.symbol}</div>
          <div class="asset-worth">$${asset.value.toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
        </div>
        <div class="asset-change ${asset.change24h >= 0 ? 'positive' : 'negative'}">
          ${asset.change24h >= 0 ? '+' : ''}${asset.change24h.toFixed(2)}%
        </div>
      `;
      container.appendChild(element);
    });
  }

  static initCharts() {
    // Initialisation du graphique principal
    this.portfolioChart = new Chart(document.getElementById('portfolio-chart'), {
      type: 'line',
      data: {
        labels: Array.from({ length: 30 }, (_, i) => {
          const date = new Date();
          date.setDate(date.getDate() - (30 - i));
          return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
        }),
        datasets: [{
          label: 'Valeur du portefeuille',
          data: Array.from({ length: 30 }, () => Math.random() * 5000 + 10000),
          borderColor: 'var(--primary)',
          backgroundColor: 'rgba(41, 98, 255, 0.1)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            grid: { color: 'var(--border-color)' },
            ticks: { callback: value => '$' + value.toLocaleString() }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });
  }

  static updateAllocationChart(allocation) {
    if (this.allocationChart) {
      this.allocationChart.data.labels = Object.keys(allocation);
      this.allocationChart.data.datasets[0].data = Object.values(allocation);
      this.allocationChart.update();
    } else {
      this.allocationChart = new Chart(document.getElementById('allocation-chart'), {
        type: 'doughnut',
        data: {
          labels: Object.keys(allocation),
          datasets: [{
            data: Object.values(allocation),
            backgroundColor: [
              '#f7931a', // BTC
              '#627eea', // ETH
              '#00ffa3', // SOL
              '#4e5d6c'  // USD
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          cutout: '70%',
          plugins: {
            legend: { position: 'right' },
            tooltip: {
              callbacks: {
                label: context => `${context.label}: ${context.raw}%`
              }
            }
          }
        }
      });
    }
  }

  static setupEventListeners() {
    // Refresh button
    document.getElementById('refresh-portfolio').addEventListener('click', () => {
      this.loadPortfolioData();
      Notifier.show('Portfolio data refreshed', 'success');
    });
    
    // Time period buttons
    document.querySelectorAll('.time-period-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        document.querySelectorAll('.time-period-btn').forEach(b => {
          b.classList.remove('active');
        });
        e.currentTarget.classList.add('active');
        
        // Ici vous chargeriez les données pour la période sélectionnée
        const period = e.currentTarget.dataset.period;
        console.log('Selected period:', period);
      });
    });
  }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => PortfolioPage.init());
