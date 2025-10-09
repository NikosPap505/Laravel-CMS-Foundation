/**
 * Drag-and-Drop Media Upload
 * Modern file upload with drag-drop, preview, and progress
 */

class MediaUploader {
    constructor(elementId) {
        this.dropZone = document.getElementById(elementId);
        if (!this.dropZone) return;

        this.fileInput = this.dropZone.querySelector('input[type="file"]');
        this.previewContainer = document.getElementById('upload-preview');
        this.uploadQueue = [];
        
        this.init();
    }

    init() {
        // Drag and drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, () => {
                this.dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, () => {
                this.dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            }, false);
        });

        // Handle drop
        this.dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            this.handleFiles(files);
        }, false);

        // Handle file input change
        if (this.fileInput) {
            this.fileInput.addEventListener('change', (e) => {
                this.handleFiles(e.target.files);
            });
            
            // Click to browse
            this.dropZone.addEventListener('click', (e) => {
                if (e.target !== this.fileInput) {
                    this.fileInput.click();
                }
            });
        }

        // Paste support
        document.addEventListener('paste', (e) => {
            if (e.clipboardData.files.length > 0) {
                this.handleFiles(e.clipboardData.files);
                window.toast.info('Images pasted from clipboard!');
            }
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleFiles(files) {
        [...files].forEach(file => {
            if (this.validateFile(file)) {
                this.uploadFile(file);
            }
        });
    }

    validateFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!validTypes.includes(file.type)) {
            window.toast.error(`Invalid file type: ${file.name}. Only images allowed.`);
            return false;
        }

        if (file.size > maxSize) {
            window.toast.error(`File too large: ${file.name}. Max size is 10MB.`);
            return false;
        }

        return true;
    }

    async uploadFile(file) {
        const preview = this.createPreview(file);
        
        const formData = new FormData();
        formData.append('file', file);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        try {
            const xhr = new XMLHttpRequest();
            
            // Progress tracking
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    this.updateProgress(preview, percentComplete);
                }
            });

            // Upload complete
            xhr.addEventListener('load', () => {
                if (xhr.status === 200 || xhr.status === 201) {
                    const response = JSON.parse(xhr.responseText);
                    this.onUploadSuccess(preview, response);
                    window.toast.success(`✅ ${file.name} uploaded successfully!`);
                } else {
                    this.onUploadError(preview, 'Upload failed');
                    window.toast.error(`❌ Failed to upload ${file.name}`);
                }
            });

            // Upload error
            xhr.addEventListener('error', () => {
                this.onUploadError(preview, 'Network error');
                window.toast.error(`❌ Network error uploading ${file.name}`);
            });

            xhr.open('POST', '/admin/media');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.send(formData);

        } catch (error) {
            this.onUploadError(preview, error.message);
            window.toast.error(`❌ Error: ${error.message}`);
        }
    }

    createPreview(file) {
        if (!this.previewContainer) {
            this.previewContainer = document.createElement('div');
            this.previewContainer.id = 'upload-preview';
            this.previewContainer.className = 'grid grid-cols-2 md:grid-cols-4 gap-4 mt-4';
            this.dropZone.parentNode.appendChild(this.previewContainer);
        }

        const preview = document.createElement('div');
        preview.className = 'relative border border-border rounded-lg p-2 bg-surface';
        
        // Create image preview
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.innerHTML = `
                <div class="relative">
                    <img src="${e.target.result}" class="w-full h-32 object-cover rounded" alt="Preview">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded upload-progress">
                        <div class="text-white text-sm">0%</div>
                    </div>
                </div>
                <p class="text-xs text-text-secondary mt-2 truncate">${file.name}</p>
                <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
                    <div class="bg-blue-600 h-1 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            `;
        };
        reader.readAsDataURL(file);

        this.previewContainer.appendChild(preview);
        return preview;
    }

    updateProgress(preview, percent) {
        const progressBar = preview.querySelector('.bg-blue-600');
        const progressText = preview.querySelector('.upload-progress div');
        
        if (progressBar) {
            progressBar.style.width = `${percent}%`;
        }
        if (progressText) {
            progressText.textContent = `${Math.round(percent)}%`;
        }
    }

    onUploadSuccess(preview, response) {
        setTimeout(() => {
            const progressOverlay = preview.querySelector('.upload-progress');
            if (progressOverlay) {
                progressOverlay.remove();
            }
            
            // Add success indicator
            const img = preview.querySelector('img');
            if (img) {
                const successBadge = document.createElement('div');
                successBadge.className = 'absolute top-2 right-2 bg-green-500 text-white rounded-full p-1';
                successBadge.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                `;
                img.parentNode.appendChild(successBadge);
            }
            
            // Reload page after delay to show new media
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }, 500);
    }

    onUploadError(preview, message) {
        const progressOverlay = preview.querySelector('.upload-progress');
        if (progressOverlay) {
            progressOverlay.innerHTML = `
                <div class="text-red-500 text-xs font-semibold">
                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <div>Failed</div>
                </div>
            `;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const mediaDropZone = document.getElementById('media-dropzone');
    if (mediaDropZone) {
        window.mediaUploader = new MediaUploader('media-dropzone');
    }
});

export default MediaUploader;
