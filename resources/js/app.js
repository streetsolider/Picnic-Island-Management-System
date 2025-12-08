import './bootstrap';
import { initTheme, toggleTheme } from './theme';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.toggleTheme = toggleTheme;
