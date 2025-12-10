import './bootstrap';
import { initTheme, toggleTheme } from './theme';
import Panzoom from '@panzoom/panzoom';
import intersect from '@alpinejs/intersect';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import Chart from 'chart.js/auto';

// Use Livewire's bundled Alpine.js
// Register Alpine plugins before Livewire starts
document.addEventListener('livewire:init', () => {
    // Alpine is available as window.Alpine from Livewire
    if (window.Alpine) {
        window.Alpine.plugin(intersect);
    }
});

window.Panzoom = Panzoom;
window.Swiper = Swiper;
window.toggleTheme = toggleTheme;
window.Chart = Chart;

