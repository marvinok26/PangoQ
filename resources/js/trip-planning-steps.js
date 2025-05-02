// public/js/trip-planning-steps.js

document.addEventListener('DOMContentLoaded', function() {
    // Get all the planning step buttons
    const planningStepButtons = document.querySelectorAll('[data-planning-step]');
    
    // Add click event listeners to all planning step buttons
    planningStepButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Dispatch the navigate-step event to increment the step counter
            window.dispatchEvent(new CustomEvent('navigate-step'));
        });
    });
    
    // For form submissions in the planning process
    const planningForms = document.querySelectorAll('form[data-planning-form]');
    planningForms.forEach(form => {
        form.addEventListener('submit', function() {
            // Dispatch the navigate-step event on form submission
            window.dispatchEvent(new CustomEvent('navigate-step'));
        });
    });
    
    // Add event listeners to trip planning navigation elements
    const tripNavLinks = document.querySelectorAll('.trip-nav-link, .itinerary-nav-link, .activity-button');
    tripNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            window.dispatchEvent(new CustomEvent('navigate-step'));
        });
    });
    
    // Function to manually trigger the login popup
    window.showLoginPopup = function() {
        window.dispatchEvent(new CustomEvent('show-login-popup'));
    };
    
    // Optional: Trigger login popup after some time of inactivity for users without accounts
    if (!document.cookie.includes('laravel_session') && !localStorage.getItem('login_popup_dismissed')) {
        let inactivityTimer;
        
        const resetTimer = function() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(function() {
                window.showLoginPopup();
            }, 120000); // Show after 2 minutes of inactivity
        };
        
        // Events that reset the inactivity timer
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(function(event) {
            document.addEventListener(event, resetTimer, true);
        });
        
        // Initial timer start
        resetTimer();
    }
});