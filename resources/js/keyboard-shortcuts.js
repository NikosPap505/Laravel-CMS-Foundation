/**
 * Keyboard Shortcuts
 * Power-user keyboard shortcuts for faster workflow
 */

class KeyboardShortcuts {
    constructor() {
        this.shortcuts = {
            // Save (Ctrl+S or Cmd+S)
            's': { ctrl: true, action: () => this.save() },
            // Publish (Ctrl+P or Cmd+P)
            'p': { ctrl: true, action: () => this.publish() },
            // Draft (Ctrl+D or Cmd+D)
            'd': { ctrl: true, action: () => this.saveDraft() },
            // Search (/)
            '/': { ctrl: false, action: () => this.focusSearch() },
            // Help (?)
            '?': { ctrl: false, shift: true, action: () => this.showHelp() },
            // Escape (close modals)
            'Escape': { ctrl: false, action: () => this.closeModals() }
        };
        
        this.init();
    }

    init() {
        document.addEventListener('keydown', (e) => {
            // Don't trigger if user is typing in input/textarea
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName) && e.key !== 'Escape') {
                return;
            }

            const key = e.key.toLowerCase();
            const shortcut = this.shortcuts[key] || this.shortcuts[e.key];

            if (!shortcut) return;

            // Check modifiers
            const ctrlMatch = !shortcut.ctrl || (e.ctrlKey || e.metaKey);
            const shiftMatch = !shortcut.shift || e.shiftKey;

            if (ctrlMatch && shiftMatch) {
                e.preventDefault();
                shortcut.action();
            }
        });
    }

    save() {
        const saveButton = document.querySelector('button[type="submit"]');
        if (saveButton && !saveButton.disabled) {
            saveButton.click();
            window.toast.info('Saving...');
        }
    }

    publish() {
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = 'published';
            this.save();
        }
    }

    saveDraft() {
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = 'draft';
            this.save();
        }
    }

    focusSearch() {
        const searchInput = document.querySelector('input[type="search"], input[name="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }

    showHelp() {
        const helpHtml = `
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" id="shortcuts-modal">
                <div class="bg-surface rounded-lg p-6 max-w-md w-full border border-border">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-text-primary">⌨️ Keyboard Shortcuts</h3>
                        <button onclick="document.getElementById('shortcuts-modal').remove()" class="text-text-secondary hover:text-text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Save</span>
                            <kbd class="px-2 py-1 bg-background border border-border rounded text-accent font-mono">Ctrl+S</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Publish</span>
                            <kbd class="px-2 py-1 bg-background border border-border rounded text-accent font-mono">Ctrl+P</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Save as Draft</span>
                            <kbd class="px-2 py-1 bg-background border border-border rounded text-accent font-mono">Ctrl+D</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Search</span>
                            <kbd class="px-2 py-1 bg-background border border-border rounded text-accent font-mono">/</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Close Modals</span>
                            <kbd class="px-2 py-1 bg-background border border-border rounded text-accent font-mono">Esc</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Show this Help</span>
                            <kbd class="px-2 py-1 bg-background border border-border rounded text-accent font-mono">?</kbd>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', helpHtml);
    }

    closeModals() {
        document.querySelectorAll('[id$="-modal"]').forEach(modal => {
            modal.remove();
        });
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.shortcuts = new KeyboardShortcuts();
});

export default KeyboardShortcuts;
