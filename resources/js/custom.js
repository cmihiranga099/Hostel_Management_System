// University Hostel Management Custom JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('University Hostel Management System Loaded');
    
    // Initialize animations
    initAnimations();
    
    // Initialize form validations
    initFormValidations();
});

// Animation Functions
function initAnimations() {
    // Fade in elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    // Observe all cards
    document.querySelectorAll('.card-custom, .hostel-package-card, .dashboard-card').forEach(el => {
        observer.observe(el);
    });
}

// Form Validation Functions
function initFormValidations() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

// Utility Functions
function showToast(message, type = 'info') {
    // Simple toast notification
    console.log(`Toast: ${message} (${type})`);
    
    // You can implement a proper toast system here
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Phone number formatting for Sri Lankan numbers
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.startsWith('94')) {
        value = '+' + value;
    } else if (value.startsWith('0')) {
        // Keep as is
    } else if (value.length === 9) {
        value = '0' + value;
    }
    
    input.value = value;
}

// NIC validation for Sri Lankan format
function validateNIC(nic) {
    // Old format: 9 digits + V/X
    const oldFormat = /^[0-9]{9}[vVxX]$/;
    // New format: 12 digits
    const newFormat = /^[0-9]{12}$/;
    
    return oldFormat.test(nic) || newFormat.test(nic);
}

// Export functions for global access
window.showToast = showToast;
window.formatPhoneNumber = formatPhoneNumber;
window.validateNIC = validateNIC;