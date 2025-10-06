/**
 * Advanced Search & Filter
 * Real-time search and filtering for admin panels
 */

class SearchFilter {
    constructor() {
        this.debounceTimer = null;
        this.init();
    }

    init() {
        // Add search functionality to admin panels
        this.addSearchToTables();
        this.addFilters();
    }

    addSearchToTables() {
        const tables = document.querySelectorAll('table');
        tables.forEach((table, index) => {
            // Check if search already exists
            if (table.previousElementSibling?.classList.contains('search-bar')) {
                return;
            }

            // Create search bar
            const searchBar = document.createElement('div');
            searchBar.className = 'search-bar mb-4 flex gap-4';
            searchBar.innerHTML = `
                <div class="flex-1">
                    <input 
                        type="search" 
                        placeholder="Search..." 
                        class="w-full px-4 py-2 bg-background border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent text-text-primary"
                        data-table-search="${index}"
                    >
                </div>
            `;

            table.parentNode.insertBefore(searchBar, table);

            // Add search functionality
            const searchInput = searchBar.querySelector('input');
            searchInput.addEventListener('input', (e) => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => {
                    this.filterTable(table, e.target.value);
                }, 300);
            });
        });
    }

    filterTable(table, searchTerm) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr');
        const term = searchTerm.toLowerCase();
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(term)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show "no results" message if needed
        this.updateNoResultsMessage(table, visibleCount, searchTerm);
    }

    updateNoResultsMessage(table, count, searchTerm) {
        const tbody = table.querySelector('tbody');
        let noResults = table.querySelector('.no-results-row');

        if (count === 0 && searchTerm) {
            if (!noResults) {
                const colCount = table.querySelectorAll('thead th').length;
                noResults = document.createElement('tr');
                noResults.className = 'no-results-row';
                noResults.innerHTML = `
                    <td colspan="${colCount}" class="px-6 py-8 text-center text-text-secondary">
                        <svg class="mx-auto h-12 w-12 text-text-secondary/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="mt-2">No results found for "${searchTerm}"</p>
                    </td>
                `;
                tbody.appendChild(noResults);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }

    addFilters() {
        // Status filter for posts/pages
        const statusElements = document.querySelectorAll('[data-status-filter]');
        statusElements.forEach(element => {
            element.addEventListener('change', (e) => {
                this.filterByStatus(e.target.value);
            });
        });
    }

    filterByStatus(status) {
        const rows = document.querySelectorAll('tbody tr:not(.no-results-row)');
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                const statusCell = row.querySelector('[data-status]');
                if (statusCell && statusCell.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.searchFilter = new SearchFilter();
});

export default SearchFilter;
