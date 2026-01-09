// Fixed payment form handling JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-payment');
    const spinner = document.getElementById('payment-spinner');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!paymentForm.checkValidity()) {
                paymentForm.classList.add('was-validated');
                return;
            }
            
            // Disable submit button and show spinner
            submitButton.disabled = true;
            spinner.classList.remove('d-none');
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Payment...';
            
            // Get form data
            const formData = new FormData(paymentForm);
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            if (csrfToken) {
                formData.append('_token', csrfToken);
            }
            
            // Submit payment via AJAX
            fetch('/payments/process', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success - redirect to success page
                    showSuccessMessage(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/payments/success/' + data.payment_id;
                    }, 2000);
                } else {
                    // Error - show error message
                    showErrorMessage(data.message || 'Payment failed. Please try again.');
                    resetSubmitButton();
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                showErrorMessage('Payment processing failed. Please check your connection and try again.');
                resetSubmitButton();
            });
        });
    }
    
    function resetSubmitButton() {
        submitButton.disabled = false;
        spinner.classList.add('d-none');
        
        // Get the amount from the form
        const amountInput = document.getElementById('amount');
        const amount = amountInput ? amountInput.value : '19,000';
        const formattedAmount = 'LKR ' + (amount ? parseFloat(amount).toLocaleString() : '19,000');
        
        submitButton.innerHTML = '<i class="fas fa-lock me-2"></i>Pay Securely - ' + formattedAmount;
    }
    
    function showSuccessMessage(message) {
        // Remove any existing alerts
        const existingAlerts = document.querySelectorAll('.payment-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show payment-alert" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        paymentForm.insertAdjacentHTML('beforebegin', alertHtml);
    }
    
    function showErrorMessage(message) {
        // Remove any existing alerts
        const existingAlerts = document.querySelectorAll('.payment-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show payment-alert" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        paymentForm.insertAdjacentHTML('beforebegin', alertHtml);
        
        // Scroll to show the error
        document.querySelector('.payment-alert').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }
    
    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    // Expiry date formatting
    const expiryInput = document.getElementById('card-expiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
    
    // CVV formatting
    const cvvInput = document.getElementById('card_cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
        });
    }
    
    // Alternative submission methods for debugging
    window.debugPayment = function() {
        console.log('Payment form data:', new FormData(paymentForm));
        console.log('Form action:', paymentForm.action);
        console.log('Form method:', paymentForm.method);
    };
    
    // Force submit function for debugging
    window.forceSubmitPayment = function() {
        const formData = new FormData(paymentForm);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('debug', 'true');
        
        console.log('Force submitting payment...');
        
        fetch('/api/payments/process', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Force submit response:', data);
            if (data.success) {
                alert('Payment processed successfully! Redirecting...');
                window.location.href = data.redirect_url;
            } else {
                alert('Payment failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Force submit error:', error);
            alert('Payment processing failed: ' + error.message);
        });
    };
});

// Additional debugging functions
window.checkPaymentRoutes = function() {
    fetch('/debug-routes')
        .then(response => response.json())
        .then(routes => {
            console.log('Available payment routes:', routes);
        })
        .catch(error => {
            console.error('Could not fetch routes:', error);
        });
};

// Test connection function  
window.testConnection = function() {
    fetch('/api/test-connection', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Connection test status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Connection test response:', data);
    })
    .catch(error => {
        console.error('Connection test failed:', error);
    });
};