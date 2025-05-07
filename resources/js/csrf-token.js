/**
 * CSRF Token script to ensure forms have proper CSRF tokens
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (token) {
        // Add token to all forms that don't have it
        document.querySelectorAll('form').forEach(form => {
            // Skip forms that already have a CSRF token
            if (!form.querySelector('input[name="_token"]')) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = token;
                form.appendChild(input);
            }
        });
        
        // Also set up CSRF protection for AJAX requests
        let originalXHR = window.XMLHttpRequest.prototype.send;
        window.XMLHttpRequest.prototype.send = function(data) {
            this.setRequestHeader('X-CSRF-TOKEN', token);
            return originalXHR.apply(this, arguments);
        };
    } else {
        console.warn('CSRF token not found. Forms may not submit correctly.');
    }
});