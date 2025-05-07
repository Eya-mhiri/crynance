assets/js/auth.js
class AuthForm {
  static init() {
    this.setupPasswordToggle();
    this.setupFormValidation();
  }

  static setupPasswordToggle() {
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', (e) => {
        const input = e.currentTarget.previousElementSibling;
        const icon = e.currentTarget.querySelector('i');
        
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    });
  }

  static setupFormValidation() {
    const forms = document.querySelectorAll('.auth-form');
    
    forms.forEach(form => {
      form.addEventListener('submit', async (e) => {
        const submitBtn = form.querySelector('button[type="submit"]');
        const loading = form.querySelector('.auth-loading');
        
        // Activation du state loading
        submitBtn.disabled = true;
        loading?.classList.add('active');
        
        // Validation côté client
        const isValid = this.validateForm(form);
        if (!isValid) {
          e.preventDefault();
          submitBtn.disabled = false;
          loading?.classList.remove('active');
        }
      });
    });
  }

  static validateForm(form) {
    let isValid = true;
    const email = form.querySelector('input[type="email"]');
    const password = form.querySelector('input[type="password"]');
    
    // Reset des erreurs
    form.querySelectorAll('.is-invalid').forEach(el => {
      el.classList.remove('is-invalid');
    });
    
    // Validation email
    if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
      email.classList.add('is-invalid');
      isValid = false;
    }
    
    // Validation password (min 8 chars)
    if (password && password.value.length < 8) {
      password.classList.add('is-invalid');
      isValid = false;
    }
    
    return isValid;
  }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => AuthForm.init());
