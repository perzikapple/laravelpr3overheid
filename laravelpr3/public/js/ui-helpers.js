/**
 * UI Helpers - Toast Notifications, Form Validation, Loading States
 */

// ===== TOAST NOTIFICATIONS =====
const Toast = {
    container: null,
    
    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },
    
    show(message, type = 'info', duration = 4000) {
        this.init();
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icons = {
            success: '✓',
            error: '✕',
            warning: '!',
            info: 'i'
        };
        
        toast.innerHTML = `
            <div class="toast-icon">${icons[type] || icons.info}</div>
            <div class="toast-content">
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
        `;
        
        this.container.appendChild(toast);
        
        if (duration > 0) {
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s reverse';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
        
        return toast;
    },
    
    success(message, duration) {
        return this.show(message, 'success', duration);
    },
    
    error(message, duration) {
        return this.show(message, 'error', duration);
    },
    
    warning(message, duration) {
        return this.show(message, 'warning', duration);
    },
    
    info(message, duration) {
        return this.show(message, 'info', duration);
    }
};

// ===== FORM VALIDATION =====
const FormValidator = {
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },
    
    validatePhone(phone) {
        const re = /^[\d\s\-\+\(\)]+$/;
        return phone.length >= 10 && re.test(phone);
    },
    
    validateRequired(value) {
        return value && value.trim().length > 0;
    },
    
    showError(input, message) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;
        
        formGroup.classList.add('has-error');
        formGroup.classList.remove('has-success');
        
        let errorEl = formGroup.querySelector('.form-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'form-error';
            input.parentNode.insertBefore(errorEl, input.nextSibling);
        }
        errorEl.textContent = message;
    },
    
    showSuccess(input) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;
        
        formGroup.classList.remove('has-error');
        formGroup.classList.add('has-success');
        
        const errorEl = formGroup.querySelector('.form-error');
        if (errorEl) errorEl.remove();
    },
    
    clearValidation(input) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;
        
        formGroup.classList.remove('has-error', 'has-success');
        const errorEl = formGroup.querySelector('.form-error');
        if (errorEl) errorEl.remove();
    },
    
    validateInput(input) {
        const type = input.type;
        const value = input.value;
        const required = input.hasAttribute('required');
        
        if (required && !this.validateRequired(value)) {
            this.showError(input, 'Dit veld is verplicht');
            return false;
        }
        
        if (value && type === 'email' && !this.validateEmail(value)) {
            this.showError(input, 'Voer een geldig e-mailadres in');
            return false;
        }
        
        if (value && type === 'tel' && !this.validatePhone(value)) {
            this.showError(input, 'Voer een geldig telefoonnummer in');
            return false;
        }
        
        if (value || !required) {
            this.showSuccess(input);
        }
        
        return true;
    },
    
    initForm(form) {
        const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea, select');
        
        inputs.forEach(input => {
            // Validate on blur
            input.addEventListener('blur', () => {
                if (input.value) {
                    this.validateInput(input);
                }
            });
            
            // Clear validation on input
            input.addEventListener('input', () => {
                if (input.classList.contains('has-error')) {
                    this.clearValidation(input);
                }
            });
        });
        
        // Validate on submit
        form.addEventListener('submit', (e) => {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!this.validateInput(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                Toast.error('Controleer de formuliervelden');
                
                // Focus first error
                const firstError = form.querySelector('.has-error input, .has-error textarea');
                if (firstError) {
                    firstError.focus();
                }
            }
        });
    }
};

// ===== LOADING STATES =====
const LoadingState = {
    setButtonLoading(button, loading = true) {
        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
            }
        }
    },
    
    showSkeleton(container) {
        container.innerHTML = `
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text" style="width: 80%;"></div>
        `;
    }
};

// ===== AUTO-INIT ON PAGE LOAD =====
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all forms with validation
    document.querySelectorAll('form').forEach(form => {
        // Only init if not a simple form (like logout)
        if (form.querySelectorAll('input:not([type="hidden"]), textarea').length > 0) {
            FormValidator.initForm(form);
        }
    });
    
    // Show success message if exists (from Laravel session)
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        Toast.success(successMessage.dataset.successMessage);
        successMessage.remove();
    }
    
    // Show error message if exists
    const errorMessage = document.querySelector('[data-error-message]');
    if (errorMessage) {
        Toast.error(errorMessage.dataset.errorMessage);
        errorMessage.remove();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { Toast, FormValidator, LoadingState };
}
