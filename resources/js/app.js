import './bootstrap';

import Alpine from 'alpinejs';

// Make Alpine available globally
document.addEventListener('livewire:initialized', () => {
    window.Alpine = Alpine;
    Alpine.start();
});


// Initialize dark mode
if (localStorage.getItem('darkMode') === null) {
    localStorage.setItem('darkMode', window.matchMedia('(prefers-color-scheme: dark)').matches);
}