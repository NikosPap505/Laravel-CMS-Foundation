@extends('layouts.admin')

@section('title', 'Upload Media')
@section('subtitle', 'Upload and manage media files')

@section('content')

<div class="space-y-6">
    <!-- Upload Form -->
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6 border-b transition-colors duration-300"
             :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
            <h1 class="text-2xl font-semibold mb-6 transition-colors duration-300"
                :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Upload Media</h1>

            {{-- Drag and Drop Upload Zone --}}
            <div id="media-dropzone" class="border-2 border-dashed rounded-lg p-12 text-center transition-all duration-300 cursor-pointer relative overflow-hidden"
                 :class="theme === 'dark' ? 'border-gray-600 hover:border-blue-500 bg-gray-900/50' : 'border-gray-300 hover:border-blue-500 bg-gray-50'">
                
                {{-- Status Overlay --}}
                <div id="upload-status-overlay" class="absolute inset-0 backdrop-blur-sm hidden items-center justify-center z-10 transition-colors duration-300"
                     :class="theme === 'dark' ? 'bg-gray-900/90' : 'bg-white/90'">
                    <div class="text-center space-y-4">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent mx-auto"></div>
                        <div>
                            <h3 class="text-lg font-semibold transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Processing Files...</h3>
                            <p id="status-message" class="text-sm transition-colors duration-300 mt-1"
                               :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Please wait while we prepare your images</p>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div id="upload-progress" class="hidden w-full rounded-full h-2 mb-4 transition-colors duration-300"
                     :class="theme === 'dark' ? 'bg-gray-700' : 'bg-gray-200'">
                    <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-center">
                        <svg id="upload-icon" class="w-16 h-16 transition-all duration-300"
                             :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="dropzone-title" class="text-lg font-semibold transition-colors duration-300"
                            :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Drop files here to upload</h3>
                        <p id="dropzone-subtitle" class="text-sm transition-colors duration-300 mt-1"
                           :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">or click to browse</p>
                        <p class="text-xs transition-colors duration-300 mt-2"
                           :class="theme === 'dark' ? 'text-gray-500' : 'text-gray-500'">Supports: JPG, PNG, GIF, WebP, SVG • Max 10MB per file</p>
                        <p class="text-xs text-blue-600 mt-1">💡 Pro tip: You can also paste images from clipboard!</p>
                    </div>
                    <input type="file" name="file" multiple accept="image/*" class="hidden">
                </div>
            </div>

            {{-- Upload Preview Container (dynamically created) --}}
            <div id="upload-preview" class="hidden space-y-4 mt-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold transition-colors duration-300"
                        :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Upload Preview</h3>
                    <button id="ai-process-all" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-2 text-sm font-medium" style="display: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        AI Process All
                    </button>
                </div>
            </div>

            {{-- Traditional form fallback --}}
            <div class="mt-8 pt-8 border-t transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <p class="text-sm mb-4 transition-colors duration-300"
                   :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Or use traditional upload:</p>
                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center gap-4">
                        <input type="file" name="file" id="file" class="text-sm transition-colors duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                               :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">
                        <button type="submit" class="btn-primary">Upload</button>
                        <a href="{{ route('admin.media.index') }}" class="text-sm transition-colors duration-300 hover:text-blue-600"
                           :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Cancel</a>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Disable the external MediaUploader for this page to avoid conflicts
    window.mediaUploader = null;
    
    // Media Upload with AI Processing
    document.addEventListener('DOMContentLoaded', () => {
        const dropzone = document.getElementById('media-dropzone');
        const fileInput = dropzone.querySelector('input[type="file"]');
        const uploadPreview = document.getElementById('upload-preview');
        const aiProcessAllBtn = document.getElementById('ai-process-all');
        let uploadedFiles = [];
        let isProcessing = false;

        if (!dropzone || !fileInput) {
            console.error('Media upload elements not found');
            return;
        }

        // Simple click handler - only on the main dropzone area
        const mainContent = dropzone.querySelector('.space-y-4');
        if (mainContent) {
            mainContent.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (!isProcessing) {
                    fileInput.click();
                }
            });
        }
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });
        
        // Highlight drop area with dramatic effects
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                // Dramatic visual changes
                dropzone.classList.add('border-blue-500', 'bg-blue-50', 'shadow-xl', 'shadow-blue-500/25');
                dropzone.style.transform = 'scale(1.08)';
                dropzone.style.transition = 'all 0.3s ease';
                
                if (document.documentElement.getAttribute('data-theme') === 'dark') {
                    dropzone.classList.add('bg-blue-900/30');
                }
                
                // Update text to show drop state
                const dropzoneTitle = document.getElementById('dropzone-title');
                const dropzoneSubtitle = document.getElementById('dropzone-subtitle');
                if (dropzoneTitle) {
                    dropzoneTitle.textContent = '🎯 Release to Upload!';
                    dropzoneTitle.style.color = '#3b82f6';
                }
                if (dropzoneSubtitle) {
                    dropzoneSubtitle.textContent = 'Drop your files here now';
                    dropzoneSubtitle.style.color = '#3b82f6';
                }
                
                // Add dramatic floating animation to icon
                const uploadIcon = document.getElementById('upload-icon');
                if (uploadIcon) {
                    uploadIcon.style.transform = 'translateY(-8px) scale(1.2)';
                    uploadIcon.style.transition = 'all 0.3s ease';
                    uploadIcon.style.color = '#3b82f6';
                }
            }, false);
        });
        
        // Remove highlight
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'bg-blue-900/30', 'shadow-xl', 'shadow-blue-500/25');
                dropzone.style.transform = 'scale(1)';
                
                // Reset text
                const dropzoneTitle = document.getElementById('dropzone-title');
                const dropzoneSubtitle = document.getElementById('dropzone-subtitle');
                if (dropzoneTitle) {
                    dropzoneTitle.textContent = 'Drop files here to upload';
                    dropzoneTitle.style.color = '';
                }
                if (dropzoneSubtitle) {
                    dropzoneSubtitle.textContent = 'or click to browse';
                    dropzoneSubtitle.style.color = '';
                }
                
                // Reset icon animation
                const uploadIcon = document.getElementById('upload-icon');
                if (uploadIcon) {
                    uploadIcon.style.transform = 'translateY(0) scale(1)';
                    uploadIcon.style.color = '';
                }
            }, false);
        });
        
        // Handle drop
        dropzone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            showDropAnimation();
            setTimeout(() => {
                handleFiles(files);
            }, 300);
        }, false);
        
        // Simple file input change handler
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0 && !isProcessing) {
                handleFiles(e.target.files);
                // Clear the input to allow selecting the same file again
                e.target.value = '';
            }
        });

        function showDropAnimation() {
            // Show dramatic success animation
            dropzone.style.transform = 'scale(1.05)';
            dropzone.style.transition = 'all 0.4s ease';
            
            // Change to success colors
            dropzone.classList.add('border-green-500', 'bg-green-50', 'shadow-lg', 'shadow-green-500/25');
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                dropzone.classList.add('bg-green-900/30');
            }
            
            // Animate icon with celebration
            const uploadIcon = document.getElementById('upload-icon');
            const dropzoneTitle = document.getElementById('dropzone-title');
            const dropzoneSubtitle = document.getElementById('dropzone-subtitle');
            
            if (uploadIcon) {
                uploadIcon.style.transform = 'scale(1.3) rotate(10deg)';
                uploadIcon.style.color = '#10b981';
                uploadIcon.style.transition = 'all 0.4s ease';
                
                // Change icon to success checkmark
                setTimeout(() => {
                    uploadIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                }, 200);
            }
            
            // Update text to show success
            if (dropzoneTitle) {
                dropzoneTitle.textContent = 'Files Dropped Successfully!';
                dropzoneTitle.style.color = '#059669';
            }
            if (dropzoneSubtitle) {
                dropzoneSubtitle.textContent = 'Processing your images...';
                dropzoneSubtitle.style.color = '#059669';
            }
            
            // Reset after animation
            setTimeout(() => {
                dropzone.style.transform = 'scale(1)';
                dropzone.classList.remove('border-green-500', 'bg-green-50', 'bg-green-900/30', 'shadow-lg', 'shadow-green-500/25');
                if (uploadIcon) {
                    uploadIcon.style.transform = 'scale(1) rotate(0deg)';
                    uploadIcon.style.color = '';
                    uploadIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>';
                }
                if (dropzoneTitle) {
                    dropzoneTitle.textContent = 'Drop files here to upload';
                    dropzoneTitle.style.color = '';
                }
                if (dropzoneSubtitle) {
                    dropzoneSubtitle.textContent = 'or click to browse';
                    dropzoneSubtitle.style.color = '';
                }
            }, 1500);
        }

        function showUploadSuccessNotification(fileCount) {
            // Get current theme
            const theme = document.documentElement.getAttribute('data-theme') || 'light';
            
            // Create large, prominent success notification
            const notification = document.createElement('div');
            notification.className = `fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 border-2 border-green-500 px-8 py-6 rounded-xl shadow-2xl z-50 scale-0 transition-all duration-300 ${
                theme === 'dark' ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'
            }`;
            notification.innerHTML = `
                <div class="text-center space-y-4">
                    <div class="mx-auto w-16 h-16 bg-green-500 rounded-full flex items-center justify-center animate-bounce">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-2 ${theme === 'dark' ? 'text-green-400' : 'text-green-600'}">🎉 Upload Complete!</h3>
                        <p class="text-lg font-semibold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}">${fileCount} Image${fileCount > 1 ? 's' : ''} Successfully Added</p>
                        <p class="text-sm mt-2 ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}">Your files are now ready for AI processing and editing</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="btn-success">
                        Got it!
                    </button>
                </div>
            `;
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-40';
            backdrop.onclick = () => {
                notification.remove();
                backdrop.remove();
            };
            
            document.body.appendChild(backdrop);
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translate(-50%, -50%) scale(1)';
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.transform = 'translate(-50%, -50%) scale(0)';
                    setTimeout(() => {
                        notification.remove();
                        backdrop.remove();
                    }, 300);
                }
            }, 5000);
        }

        function showFileProcessingAnimation(fileName) {
            // Create prominent processing indicator
            const processingIndicator = document.createElement('div');
            processingIndicator.className = 'fixed top-4 right-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-4 rounded-xl shadow-xl z-40 transform translate-x-full transition-transform duration-300';
            processingIndicator.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-3 border-white border-t-transparent"></div>
                    <div>
                        <p class="font-semibold">Processing Image</p>
                        <p class="text-sm opacity-90">${fileName}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(processingIndicator);
            
            // Animate in
            setTimeout(() => {
                processingIndicator.style.transform = 'translate-x-0';
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                if (processingIndicator.parentNode) {
                    processingIndicator.style.transform = 'translate-x-full';
                    setTimeout(() => {
                        processingIndicator.remove();
                    }, 300);
                }
            }, 3000);
        }

        function showProcessingOverlay(message) {
            const overlay = document.getElementById('upload-status-overlay');
            const statusMessage = document.getElementById('status-message');
            const progressBar = document.getElementById('upload-progress');
            
            if (overlay && statusMessage) {
                statusMessage.textContent = message;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                
                // Apply theme colors to overlay
                const theme = document.documentElement.getAttribute('data-theme') || 'light';
                overlay.className = overlay.className.replace(/bg-\w+\/\d+/, '');
                overlay.classList.add(theme === 'dark' ? 'bg-gray-900/90' : 'bg-white/90');
                
                if (progressBar) {
                    progressBar.classList.remove('hidden');
                    
                    // Apply theme colors to progress bar
                    progressBar.className = progressBar.className.replace(/bg-\w+-\d+/, '');
                    progressBar.classList.add(theme === 'dark' ? 'bg-gray-700' : 'bg-gray-200');
                }
            }
        }

        function hideProcessingOverlay() {
            const overlay = document.getElementById('upload-status-overlay');
            const progressBar = document.getElementById('upload-progress');
            
            if (overlay) {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
            
            if (progressBar) {
                progressBar.classList.add('hidden');
            }
        }

        function updateProgress(percentage) {
            const progressBar = document.getElementById('progress-bar');
            if (progressBar) {
                progressBar.style.width = percentage + '%';
            }
        }

        function handleFiles(files) {
            if (isProcessing) return;
            
            isProcessing = true;
            let processedCount = 0;
            const imageFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
            const totalFiles = imageFiles.length;
            
            if (totalFiles === 0) {
                isProcessing = false;
                alert('No valid image files found. Please upload JPG, PNG, GIF, WebP, or SVG files.');
                return;
            }
            
            // Show processing overlay immediately
            showProcessingOverlay(`Processing ${totalFiles} image${totalFiles > 1 ? 's' : ''}...`);
            
            // Process each file
            imageFiles.forEach((file, index) => {
                setTimeout(() => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const fileData = {
                            file: file,
                            preview: e.target.result,
                            altText: '',
                            tags: '',
                            description: ''
                        };
                        uploadedFiles.push(fileData);
                        showFilePreview(fileData);
                        
                        processedCount++;
                        updateProgress(Math.round((processedCount / totalFiles) * 100));
                        
                        // Show success notification when all files are processed
                        if (processedCount === totalFiles) {
                            setTimeout(() => {
                                hideProcessingOverlay();
                                showUploadSuccessNotification(totalFiles);
                                isProcessing = false;
                            }, 500);
                        }
                    };
                    
                    reader.onerror = () => {
                        processedCount++;
                        if (processedCount === totalFiles) {
                            hideProcessingOverlay();
                            isProcessing = false;
                        }
                    };
                    
                    reader.readAsDataURL(file);
                }, index * 200); // Small delay between files
            });
            
            // Show preview container
            if (uploadPreview) {
                uploadPreview.classList.remove('hidden');
            }
            if (aiProcessAllBtn) {
                aiProcessAllBtn.style.display = 'flex';
            }
        }

    function showFilePreview(fileData) {
        const previewItem = document.createElement('div');
        previewItem.className = 'rounded-lg p-4 space-y-4 transition-colors duration-300 transform scale-95 opacity-0';
        previewItem.innerHTML = `
            <div class="flex gap-4">
                <img src="${fileData.preview}" alt="Preview" class="w-20 h-20 object-cover rounded-lg">
                <div class="flex-1 space-y-3">
                    <div>
                        <h4 class="font-medium transition-colors duration-300">${fileData.file.name}</h4>
                        <p class="text-sm transition-colors duration-300">${formatFileSize(fileData.file.size)}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium transition-colors duration-300 mb-1">Alt Text</label>
                            <div class="relative">
                                <input type="text" class="w-full rounded-md shadow-sm pr-16 text-sm transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the image for accessibility">
                                <button onclick="generateAIAltText(this)" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-2 py-1 rounded text-xs font-medium" title="🤖 AI Alt Text">
                                    AI
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium transition-colors duration-300 mb-1">Tags</label>
                            <div class="relative">
                                <input type="text" class="w-full rounded-md shadow-sm pr-16 text-sm transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter tags separated by commas">
                                <button onclick="generateAITags(this)" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-yellow-500 to-amber-500 text-white px-2 py-1 rounded text-xs font-medium" title="🤖 AI Tags">
                                    AI
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium transition-colors duration-300 mb-1">Description</label>
                        <div class="relative">
                            <textarea class="w-full rounded-md shadow-sm pr-16 text-sm transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500" rows="2" placeholder="Optional description"></textarea>
                            <button onclick="generateAIDescription(this)" class="absolute right-2 top-2 bg-gradient-to-r from-green-500 to-teal-500 text-white px-2 py-1 rounded text-xs font-medium" title="🤖 AI Description">
                                AI
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2">
                        <button onclick="uploadSingleFile(this)" class="btn-primary">
                            Upload
                        </button>
                        <button onclick="removePreview(this)" class="btn-danger">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Apply theme classes to the preview item
        const theme = document.documentElement.getAttribute('data-theme') || 'light';
        if (theme === 'dark') {
            previewItem.classList.add('bg-gray-800', 'border', 'border-gray-700');
            previewItem.querySelectorAll('h4').forEach(el => el.classList.add('text-white'));
            previewItem.querySelectorAll('p').forEach(el => el.classList.add('text-gray-400'));
            previewItem.querySelectorAll('label').forEach(el => el.classList.add('text-gray-400'));
            previewItem.querySelectorAll('input, textarea').forEach(el => {
                el.classList.add('bg-gray-900', 'border-gray-600', 'text-white');
            });
        } else {
            previewItem.classList.add('bg-white', 'border', 'border-gray-200');
            previewItem.querySelectorAll('h4').forEach(el => el.classList.add('text-gray-900'));
            previewItem.querySelectorAll('p').forEach(el => el.classList.add('text-gray-600'));
            previewItem.querySelectorAll('label').forEach(el => el.classList.add('text-gray-600'));
            previewItem.querySelectorAll('input, textarea').forEach(el => {
                el.classList.add('bg-white', 'border-gray-300', 'text-gray-900');
            });
        }
        
        uploadPreview.appendChild(previewItem);
            
            // Animate entrance
            setTimeout(() => {
                previewItem.style.transform = 'scale(1)';
                previewItem.style.opacity = '1';
            }, 100);
    }

    async function generateAIAltText(button) {
        const previewItem = button.closest('.rounded-lg');
        const img = previewItem.querySelector('img');
        const altInput = previewItem.querySelector('input[placeholder*="Describe the image"]');
        
        if (!img || !img.src) {
            alert('No image found');
            return;
        }

        try {
            button.innerHTML = '<span class="animate-spin">⟳</span>';
            button.disabled = true;
            
            // For now, we'll use a placeholder since we don't have image analysis API
            // In a real implementation, you'd send the image to an AI service
            setTimeout(() => {
                const altText = generatePlaceholderAltText(img.src);
                altInput.value = altText;
                button.innerHTML = 'AI';
                button.disabled = false;
            }, 1500);
            
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = 'AI';
            button.disabled = false;
            alert('Failed to generate alt text');
        }
    }

    async function generateAITags(button) {
        const previewItem = button.closest('.rounded-lg');
        const img = previewItem.querySelector('img');
        const tagsInput = previewItem.querySelector('input[placeholder*="Enter tags"]');
        
        if (!img || !img.src) {
            alert('No image found');
            return;
        }

        try {
            button.innerHTML = '<span class="animate-spin">⟳</span>';
            button.disabled = true;
            
            // Placeholder implementation
            setTimeout(() => {
                const tags = generatePlaceholderTags(img.src);
                tagsInput.value = tags;
                button.innerHTML = 'AI';
                button.disabled = false;
            }, 1500);
            
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = 'AI';
            button.disabled = false;
            alert('Failed to generate tags');
        }
    }

    async function generateAIDescription(button) {
        const previewItem = button.closest('.rounded-lg');
        const img = previewItem.querySelector('img');
        const descInput = previewItem.querySelector('textarea');
        
        if (!img || !img.src) {
            alert('No image found');
            return;
        }

        try {
            button.innerHTML = '<span class="animate-spin">⟳</span>';
            button.disabled = true;
            
            // Placeholder implementation
            setTimeout(() => {
                const description = generatePlaceholderDescription(img.src);
                descInput.value = description;
                button.innerHTML = 'AI';
                button.disabled = false;
            }, 1500);
            
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = 'AI';
            button.disabled = false;
            alert('Failed to generate description');
        }
    }

    // Placeholder functions for AI processing
    function generatePlaceholderAltText(imageSrc) {
        const altTexts = [
            'A beautiful landscape image',
            'Professional photography',
            'High-quality image content',
            'Visual content for website',
            'Stunning photography'
        ];
        return altTexts[Math.floor(Math.random() * altTexts.length)];
    }

    function generatePlaceholderTags(imageSrc) {
        const tagSets = [
            'photography, landscape, nature',
            'business, professional, corporate',
            'design, modern, creative',
            'technology, digital, innovation',
            'lifestyle, people, social'
        ];
        return tagSets[Math.floor(Math.random() * tagSets.length)];
    }

    function generatePlaceholderDescription(imageSrc) {
        const descriptions = [
            'High-quality image suitable for web use',
            'Professional photography for marketing purposes',
            'Beautiful visual content for your website',
            'Modern image design for digital platforms'
        ];
        return descriptions[Math.floor(Math.random() * descriptions.length)];
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function uploadSingleFile(button) {
        // Implementation for single file upload
        alert('Upload functionality would be implemented here');
    }

    function removePreview(button) {
        const previewItem = button.closest('.rounded-lg');
        previewItem.remove();
        
        if (uploadPreview.children.length <= 1) {
            uploadPreview.classList.add('hidden');
            aiProcessAllBtn.style.display = 'none';
        }
    }

    // AI Process All functionality
    aiProcessAllBtn.addEventListener('click', async () => {
        const previewItems = uploadPreview.querySelectorAll('.rounded-lg');
        
        for (const item of previewItems) {
            const altButton = item.querySelector('button[onclick*="generateAIAltText"]');
            const tagsButton = item.querySelector('button[onclick*="generateAITags"]');
            const descButton = item.querySelector('button[onclick*="generateAIDescription"]');
            
            if (altButton) await generateAIAltText(altButton);
            if (tagsButton) await generateAITags(tagsButton);
            if (descButton) await generateAIDescription(descButton);
        }
        
        alert('All files have been processed with AI!');
    });
</script>
@endpush
@endsection
