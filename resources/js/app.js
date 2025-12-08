import './bootstrap';
import { initTheme, toggleTheme } from './theme';
import Panzoom from '@panzoom/panzoom';
import intersect from '@alpinejs/intersect';

// Use Livewire's bundled Alpine.js
// Register Alpine plugins before Livewire starts
document.addEventListener('livewire:init', () => {
    // Alpine is available as window.Alpine from Livewire
    if (window.Alpine) {
        window.Alpine.plugin(intersect);
    }
});

window.Panzoom = Panzoom;
window.toggleTheme = toggleTheme;
