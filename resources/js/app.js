import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

Alpine.plugin(intersect);

// --- Dark Mode Toggle ---
Alpine.store('darkMode', {
    on: localStorage.getItem('darkMode') === 'true' ||
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    
    toggle() {
        this.on = !this.on;
        localStorage.setItem('darkMode', this.on);
        document.documentElement.classList.toggle('dark', this.on);
    },

    init() {
        document.documentElement.classList.toggle('dark', this.on);
    }
});

// --- Toast Notification System ---
Alpine.store('toasts', {
    items: [],
    
    add(message, type = 'success') {
        const id = Date.now();
        this.items.push({ id, message, type, leaving: false });
        setTimeout(() => this.remove(id), 4000);
    },
    
    remove(id) {
        const idx = this.items.findIndex(t => t.id === id);
        if (idx > -1) {
            this.items[idx].leaving = true;
            setTimeout(() => {
                this.items = this.items.filter(t => t.id !== id);
            }, 300);
        }
    }
});

// --- Animated Counter Component ---
Alpine.data('animatedCounter', (target, duration = 1500) => ({
    current: 0,
    target: parseFloat(target) || 0,
    
    init() {
        this.animateTo(this.target, duration);
    },
    
    animateTo(target, dur) {
        const start = this.current;
        const range = target - start;
        const startTime = performance.now();
        
        const step = (now) => {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / dur, 1);
            // ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            this.current = start + range * eased;
            
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                this.current = target;
            }
        };
        
        requestAnimationFrame(step);
    },

    get formatted() {
        return new Intl.NumberFormat('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(this.current);
    }
}));

// --- Confirmation Modal ---
Alpine.data('confirmModal', () => ({
    open: false,
    title: '',
    message: '',
    formAction: null,
    
    show(title, message, formEl) {
        this.title = title;
        this.message = message;
        this.formAction = formEl;
        this.open = true;
    },
    
    confirm() {
        if (this.formAction) {
            this.formAction.submit();
        }
        this.open = false;
    },
    
    cancel() {
        this.open = false;
        this.formAction = null;
    }
}));

window.Alpine = Alpine;
Alpine.start();
