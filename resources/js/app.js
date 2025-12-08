import './bootstrap';
import { initTheme, toggleTheme } from './theme';
import intersect from '@alpinejs/intersect';

// Register Alpine plugins before Livewire starts Alpine
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(intersect);
});

window.toggleTheme = toggleTheme;
