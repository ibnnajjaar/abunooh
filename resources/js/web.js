import Prism from 'prismjs';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-liquid';
import 'prismjs/components/prism-markdown';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-scss';
import 'prismjs/plugins/toolbar/prism-toolbar.min.js';
import 'prismjs/plugins/toolbar/prism-toolbar.min.css';
import 'prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard';
import 'prismjs/plugins/line-numbers/prism-line-numbers';
import 'prismjs/plugins/line-numbers/prism-line-numbers.min.css';
import '../css/theme.css';

Prism.highlightAll();

// Theme Switcher
const themeSwitcher = () => {
    const html = document.documentElement;
    const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

    if (theme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }

    const toggleTheme = () => {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    };

    window.toggleTheme = toggleTheme;
};

themeSwitcher();

// Kong Editorial Interactivity
document.addEventListener('DOMContentLoaded', () => {
    // Global Cursor Glow
    const glow = document.createElement('div');
    glow.classList.add('cursor-glow');
    document.body.appendChild(glow);

    let x = -500, y = -500, frame = 0;

    function paintGlow() {
        glow.style.transform = `translate3d(${x}px, ${y}px, 0)`;
        frame = 0;
    }

    window.addEventListener('pointermove', (event) => {
        if (event.pointerType === 'touch') return;
        x = event.clientX;
        y = event.clientY;
        glow.classList.add('visible');
        if (!frame) frame = requestAnimationFrame(paintGlow);
    }, { passive: true });

    document.documentElement.addEventListener('mouseleave', () => {
        glow.classList.remove('visible');
    });

    // Card Local Spotlight
    const updateCardSpotlight = (card, event) => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
        card.style.setProperty('--my', `${event.clientY - rect.top}px`);
    };

    document.querySelectorAll('.kong-card').forEach((card) => {
        card.addEventListener('pointermove', (event) => {
            updateCardSpotlight(card, event);
        }, { passive: true });
    });
});
