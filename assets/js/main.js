

// Gestion des notifications
class Notifier {
  static show(message, type = 'success', duration = 5000) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
      <span>${message}</span>
      <button class="close-btn">&times;</button>
    `;
    
    document.getElementById('alerts-container').appendChild(alert);
    
    const removeAlert = () => alert.remove();
    
    alert.querySelector('.close-btn').addEventListener('click', removeAlert);
    setTimeout(removeAlert, duration);
  }
}

// Gestion du thème (dark/light)
class ThemeManager {
  static init() {
    const toggle = document.getElementById('theme-toggle');
    if (toggle) {
      toggle.addEventListener('click', () => {
        document.body.classList.toggle('light-mode');
        localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
      });
      
      const savedTheme = localStorage.getItem('theme') || 'dark';
      if (savedTheme === 'light') document.body.classList.add('light-mode');
    }
  }
}

// Initialisation globale
document.addEventListener('DOMContentLoaded', () => {
  ThemeManager.init();
  
  // Tooltips Bootstrap (si utilisé)
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
});

// Helper pour les requêtes API
class ApiClient {
  static async fetch(endpoint, options = {}) {
    const response = await fetch(`/api/${endpoint}`, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      ...options
    });
    
    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Request failed');
    }
    
    return response.json();
  }
}
