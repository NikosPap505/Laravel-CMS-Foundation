/**
 * Bulk Actions System
 * Select multiple items and perform batch operations
 */

class BulkActions {
    constructor() {
        this.selectedItems = new Set();
        this.init();
    }

    init() {
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                this.toggleAll(e.target.checked);
            });
        }

        // Individual checkboxes
        document.querySelectorAll('.bulk-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.toggleItem(e.target.value, e.target.checked);
            });
        });

        // Bulk action buttons
        document.querySelectorAll('[data-bulk-action]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const action = button.dataset.bulkAction;
                this.performAction(action);
            });
        });

        // Update UI on page load
        this.updateUI();
    }

    toggleAll(checked) {
        document.querySelectorAll('.bulk-checkbox').forEach(checkbox => {
            checkbox.checked = checked;
            this.toggleItem(checkbox.value, checked);
        });
    }

    toggleItem(id, checked) {
        if (checked) {
            this.selectedItems.add(id);
        } else {
            this.selectedItems.delete(id);
        }
        this.updateUI();
    }

    updateUI() {
        const count = this.selectedItems.size;
        const bulkBar = document.getElementById('bulk-action-bar');
        const countElement = document.getElementById('selected-count');

        if (bulkBar) {
            if (count > 0) {
                bulkBar.classList.remove('hidden');
                if (countElement) {
                    countElement.textContent = count;
                }
            } else {
                bulkBar.classList.add('hidden');
            }
        }

        // Update select all checkbox state
        const selectAllCheckbox = document.getElementById('select-all');
        const totalCheckboxes = document.querySelectorAll('.bulk-checkbox').length;
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count === totalCheckboxes && count > 0;
            selectAllCheckbox.indeterminate = count > 0 && count < totalCheckboxes;
        }
    }

    performAction(action) {
        if (this.selectedItems.size === 0) {
            window.toast.warning('Please select at least one item');
            return;
        }

        const items = Array.from(this.selectedItems);
        
        // Confirmation for destructive actions
        if (action === 'delete' || action === 'trash') {
            if (!confirm(`Are you sure you want to ${action} ${items.length} item(s)?`)) {
                return;
            }
        }

        // Show loading
        window.toast.info(`Processing ${items.length} item(s)...`);

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Get current URL to determine resource type
        const currentPath = window.location.pathname;
        let endpoint = '';
        
        if (currentPath.includes('/posts')) {
            endpoint = '/admin/posts/bulk-action';
        } else if (currentPath.includes('/pages')) {
            endpoint = '/admin/pages/bulk-action';
        } else if (currentPath.includes('/comments')) {
            endpoint = '/admin/comments/bulk-action';
        } else if (currentPath.includes('/media')) {
            endpoint = '/admin/media/bulk-action';
        }

        // Send request
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                ids: items
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.toast.success(data.message || `Successfully ${action}ed ${items.length} item(s)`);
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                window.toast.error(data.message || 'Action failed');
            }
        })
        .catch(error => {
            window.toast.error('An error occurred: ' + error.message);
        });
    }

    clearSelection() {
        this.selectedItems.clear();
        document.querySelectorAll('.bulk-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        this.updateUI();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.bulkActions = new BulkActions();
});

export default BulkActions;
