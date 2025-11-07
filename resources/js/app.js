
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// Don't require bootstrap.js because jQuery and Bootstrap are already loaded globally
// This prevents conflicts with admin.js
// require('./bootstrap');

// Use globally available lodash if needed
if (typeof window._ === 'undefined') {
    window._ = require('lodash');
}

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Register all Vue components globally
Vue.component('example', require('./components/Example.vue'));
Vue.component('password-strength', require('./components/PasswordStrength.vue'));
Vue.component('content-selector', require('./components/ContentSelector.vue'));
Vue.component('link-selector', require('./components/LinkSelector.vue'));

// Initialize Vue after DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure all scripts are loaded
    setTimeout(function() {
        // Look for main #app container
        const appElement = document.getElementById('app');
        
        if (appElement) {
            const app = new Vue({
                el: '#app',
                data: {
                    // Password form validation state
                    isFormValid: false,
                    
                    // Add other shared data properties here as needed
                },
                methods: {
                    // Password strength component validation handler
                    updateFormValidity(isValid) {
                        this.isFormValid = isValid;
                    },
                    
                    // Add other shared methods here as needed
                },
                mounted() {
                    console.log('Vue app initialized');
                }
            });

            // Expose Vue instance globally for access from child components or external scripts
            window.app = app;
        }
    }, 200);
});
