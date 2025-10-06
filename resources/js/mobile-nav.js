/**
 * Mobile Navigation
 * Responsive mobile menu with smooth animations
 */

class MobileNavigation {
    constructor() {
        this.init();
    }

    init() {
        // Create mobile menu button if it doesn't exist
        const nav = document.querySelector('nav[x-data]');
        if (nav && !document.getElementById('mobile-menu-button')) {
            this.enhanceMobileMenu();
        }

        // Add touch gestures for mobile
        this.addTouchGestures();
        
        // Make tables responsive
        this.makeTablesResponsive();
    }

    enhanceMobileMenu() {
        // The menu is already handled by Alpine.js in the navigation component
        // We just need to ensure it works smoothly on mobile
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            const nav = document.querySelector('nav[x-data]');
            const button = document.getElementById('mobile-menu-button');
            
            if (nav && button && !nav.contains(e.target)) {
                // Use Alpine's data to close menu
                const alpineData = nav.__x?.$data;
                if (alpineData && alpineData.open) {
                    alpineData.open = false;
                }
            }
        });
    }

    addTouchGestures() {
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleGesture();
        });
    }

    handleGesture() {
        // You can add swipe gestures here if needed
        // For now, we keep it simple
    }

    makeTablesResponsive() {
        const tables = document.querySelectorAll('table:not(.responsive-table-wrapped)');
        tables.forEach(table => {
            if (!table.closest('.overflow-x-auto')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'overflow-x-auto -mx-4 sm:mx-0';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
                table.classList.add('responsive-table-wrapped');
            }
        });
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.mobileNav = new MobileNavigation();
});

export default MobileNavigation;
