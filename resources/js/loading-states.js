/**
 * Loading States & Progress Indicators
 * Visual feedback for all user actions
 */

class LoadingStates {
    constructor() {
        this.init();
    }

    init() {
        // Add loading states to all forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton && !submitButton.dataset.noLoading) {
                    this.setButtonLoading(submitButton);
                }
            });
        });

        // Add loading states to AJAX buttons
        document.querySelectorAll('[data-loading-text]').forEach(button => {
            button.addEventListener('click', () => {
                this.setButtonLoading(button);
            });
        });

        // Skeleton loaders for content
        this.addSkeletonLoaders();
    }

    setButtonLoading(button) {
        const originalText = button.innerHTML;
        button.dataset.originalText = originalText;
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        
        const loadingText = button.dataset.loadingText || 'Processing...';
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${loadingText}
        `;
    }

    resetButton(button) {
        if (button.dataset.originalText) {
            button.innerHTML = button.dataset.originalText;
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    addSkeletonLoaders() {
        // Add skeleton screens for tables while loading
        const tables = document.querySelectorAll('table[data-skeleton]');
        tables.forEach(table => {
            if (table.querySelector('tbody').children.length === 0) {
                this.showTableSkeleton(table);
            }
        });
    }

    showTableSkeleton(table) {
        const tbody = table.querySelector('tbody');
        const colCount = table.querySelectorAll('thead th').length;
        
        for (let i = 0; i < 5; i++) {
            const row = document.createElement('tr');
            for (let j = 0; j < colCount; j++) {
                const cell = document.createElement('td');
                cell.className = 'px-6 py-4';
                cell.innerHTML = '<div class="h-4 bg-gray-200 rounded animate-pulse"></div>';
                row.appendChild(cell);
            }
            tbody.appendChild(row);
        }
    }

    showProgress(percent) {
        let progressBar = document.getElementById('global-progress');
        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.id = 'global-progress';
            progressBar.className = 'fixed top-0 left-0 right-0 h-1 bg-blue-600 z-50 transition-all duration-300';
            progressBar.style.width = '0%';
            document.body.appendChild(progressBar);
        }
        progressBar.style.width = `${percent}%`;
        
        if (percent >= 100) {
            setTimeout(() => {
                progressBar.remove();
            }, 500);
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.loadingStates = new LoadingStates();
});

export default LoadingStates;
