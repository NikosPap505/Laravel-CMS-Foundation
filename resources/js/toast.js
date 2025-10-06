/**
 * Toast Notification System
 * Beautiful, modern toast notifications for the CMS
 */

class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    show(message, type = 'success', duration = 4000) {
        const toast = this.createToast(message, type);
        this.container.appendChild(toast);

        // Trigger entrance animation
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 10);

        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                this.dismiss(toast);
            }, duration);
        }

        return toast;
    }

    createToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `transform translate-x-full opacity-0 transition-all duration-300 ease-out 
                          flex items-start gap-3 p-4 rounded-lg shadow-lg max-w-md min-w-[320px] 
                          ${this.getTypeClasses(type)}`;

        const icon = this.getIcon(type);
        const closeBtn = `
            <button onclick="window.toast.dismiss(this.closest('[role=alert]'))" 
                    class="flex-shrink-0 ml-auto text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="flex-shrink-0">${icon}</div>
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-medium text-white">${message}</p>
            </div>
            ${closeBtn}
        `;

        return toast;
    }

    getTypeClasses(type) {
        const classes = {
            success: 'bg-green-600 text-white',
            error: 'bg-red-600 text-white',
            warning: 'bg-yellow-600 text-white',
            info: 'bg-blue-600 text-white'
        };
        return classes[type] || classes.success;
    }

    getIcon(type) {
        const icons = {
            success: `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `,
            error: `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `,
            warning: `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            `,
            info: `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `
        };
        return icons[type] || icons.info;
    }

    dismiss(toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    success(message, duration = 4000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 4000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 4000) {
        return this.show(message, 'info', duration);
    }
}

// Initialize global toast instance
window.toast = new ToastNotification();

// Auto-show Laravel session flash messages
document.addEventListener('DOMContentLoaded', () => {
    // Success messages
    const successMsg = document.querySelector('[data-flash-success]');
    if (successMsg) {
        window.toast.success(successMsg.dataset.flashSuccess);
    }

    // Error messages
    const errorMsg = document.querySelector('[data-flash-error]');
    if (errorMsg) {
        window.toast.error(errorMsg.dataset.flashError);
    }

    // Warning messages
    const warningMsg = document.querySelector('[data-flash-warning]');
    if (warningMsg) {
        window.toast.warning(warningMsg.dataset.flashWarning);
    }

    // Info messages
    const infoMsg = document.querySelector('[data-flash-info]');
    if (infoMsg) {
        window.toast.info(infoMsg.dataset.flashInfo);
    }
});

export default ToastNotification;
