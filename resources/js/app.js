import './bootstrap';
import { initTheme, toggleTheme } from './theme';
import Alpine from 'alpinejs';
import Panzoom from '@panzoom/panzoom';

window.Alpine = Alpine;
window.Panzoom = Panzoom;

Alpine.start();

window.toggleTheme = toggleTheme;
