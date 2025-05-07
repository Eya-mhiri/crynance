assets/js/profile.js
class ProfilePage {
  static init() {
    this.loadProfileData();
    this.setupEventListeners();
    this.init2FASetup();
  }

  static async loadProfileData() {
    try {
      const data = await this.fetchProfileData();
      this.renderProfile(data);
    } catch (error) {
      console.error('Failed to load profile data:', error);
      Notifier.show('Failed to load profile', 'error');
    }
  }

  static async fetchProfileData() {
    // Simulated API call
    return {
      user: {
        name: 'John Doe',
        email: 'john@example.com',
        joinDate: '2023-01-15',
        avatarInitials: 'JD'
      },
      stats: {
        trades: 42,
        portfolioValue: 12500.34,
        verified: true,
        twoFactorEnabled: false
      }
    };
  }

  static renderProfile(data) {
    document.getElementById('profile-avatar').textContent = data.user.avatarInitials;
    document.getElementById('profile-name').textContent = data.user.name;
    document.getElementById('profile-email').textContent = data.user.email;
    document.getElementById('member-since').textContent = new Date(data.user.joinDate).toLocaleDateString('fr-FR', { year: 'numeric', month: 'long' });
    
    document.getElementById('trades-count').textContent = data.stats.trades;
    document.getElementById('portfolio-value').textContent = `$${data.stats.portfolioValue.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    
    const verificationBadge = document.getElementById('verification-badge');
    verificationBadge.textContent = data.stats.verified ? 'Vérifié' : 'Non vérifié';
    verificationBadge.className = `security-badge ${data.stats.verified ? 'enabled' : 'disabled'}`;
    
    const twoFactorBadge = document.getElementById('2fa-badge');
    twoFactorBadge.textContent = data.stats.twoFactorEnabled ? 'Activé' : 'Désactivé';
    twoFactorBadge.className = `security-badge ${data.stats.twoFactorEnabled ? 'enabled' : 'disabled'}`;
  }

  static init2FASetup() {
    // Initialisation du QR code pour 2FA
    const qrElement = document.getElementById('2fa-qr-code');
    if (qrElement) {
      // En production, vous généreriez un vrai QR code
      qrElement.innerHTML = '<div class="text-center p-4 text-muted">[QR Code would appear here]</div>';
    }
  }

  static setupEventListeners() {
    // Form submission
    document.getElementById('profile-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const form = e.currentTarget;
      const formData = new FormData(form);
      const submitBtn = form.querySelector('button[type="submit"]');
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000));
        Notifier.show('Profile updated successfully', 'success');
      } catch (error) {
        Notifier.show('Failed to update profile', 'error');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Changes';
      }
    });
    
    // 2FA toggle
    document.getElementById('toggle-2fa').addEventListener('click', async (e) => {
      const isEnabled = e.currentTarget.dataset.enabled === 'true';
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 800));
        
        e.currentTarget.dataset.enabled = !isEnabled;
        document.getElementById('2fa-badge').textContent = isEnabled ? 'Désactivé' : 'Activé';
        document.getElementById('2fa-badge').className = `security-badge ${isEnabled ? 'disabled' : 'enabled'}`;
        
        Notifier.show(`Two-factor authentication ${isEnabled ? 'disabled' : 'enabled'}`, 'success');
      } catch (error) {
        Notifier.show('Failed to update 2FA settings', 'error');
      }
    });
  }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => ProfilePage.init());
