import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global error handling for forms
Alpine.data('formHandler', () => ({
    loading: false,
    submitForm(event) {
        this.loading = true;
        // Let the form submit naturally, loading will be reset on page reload
    }
}));

// Auto-hide flash messages
Alpine.data('flashMessage', () => ({
    show: true,
    init() {
        setTimeout(() => {
            this.show = false;
        }, 5000);
    }
}));

// Form validation feedback
Alpine.data('validation', () => ({
    errors: {},
    hasError(field) {
        return this.errors[field] && this.errors[field].length > 0;
    },
    getError(field) {
        return this.errors[field] ? this.errors[field][0] : '';
    }
}));

Alpine.start();
