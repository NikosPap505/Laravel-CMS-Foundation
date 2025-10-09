<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Usage Widget Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-surface { background-color: #f8fafc; }
        .bg-background { background-color: #ffffff; }
        .text-text-primary { color: #1e293b; }
        .text-text-secondary { color: #64748b; }
        .border-border { border-color: #e2e8f0; }
        .text-accent { color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">AI Usage Widget Test</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Test the AI Assistant Modal</h2>
            <p class="text-gray-600 mb-4">Click the button below to open the AI Assistant modal and see the usage widget:</p>
            
            <button onclick="openAIAssistant()" class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-white px-6 py-3 rounded-xl shadow-2xl hover:shadow-3xl transition-all duration-300 hover:scale-110 flex items-center gap-2 font-bold text-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                Open AI Assistant
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Direct Widget Test</h2>
            <p class="text-gray-600 mb-4">This shows the AI usage widget directly on the page:</p>
            
            <!-- AI Usage Widget -->
            <div id="ai-usage-widget" class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-blue-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            AI Credits
                        </h3>
                        <p class="text-sm text-blue-700">Loading usage data...</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-900" id="credits-display">$100.00</div>
                        <div class="text-xs text-blue-600" id="usage-status">Healthy</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-blue-600 mb-1">
                        <span>Usage Today</span>
                        <span id="today-requests">0 requests</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div id="usage-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            
            <button onclick="loadAIUsageData()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Load Usage Data
            </button>
        </div>
    </div>

    <script>
        // Include the AI usage functions from the post form
        async function loadAIUsageData() {
            try {
                const response = await fetch('/admin/ai/usage', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    updateAIUsageDisplay(data.data);
                } else {
                    showDefaultUsageData();
                }
            } catch (error) {
                console.error('Failed to load AI usage data:', error);
                showDefaultUsageData();
            }
        }

        function showDefaultUsageData() {
            console.log('Showing default AI usage data');
            const defaultData = {
                credits_remaining: 100.00,
                requests_today: 0,
                usage_percentage: 0,
                status: 'healthy'
            };
            updateAIUsageDisplay(defaultData);
        }

        function updateAIUsageDisplay(usageData) {
            console.log('Updating AI usage display with data:', usageData);
            const creditsDisplay = document.getElementById('credits-display');
            const usageStatus = document.getElementById('usage-status');
            const todayRequests = document.getElementById('today-requests');
            const usageBar = document.getElementById('usage-bar');
            
            console.log('Found elements:', {
                creditsDisplay: !!creditsDisplay,
                usageStatus: !!usageStatus,
                todayRequests: !!todayRequests,
                usageBar: !!usageBar
            });

            if (creditsDisplay) {
                creditsDisplay.textContent = `$${usageData.credits_remaining.toFixed(2)}`;
            }

            if (usageStatus) {
                usageStatus.textContent = capitalizeFirst(usageData.status);
                
                const statusColors = {
                    'healthy': 'text-green-600',
                    'moderate': 'text-yellow-600',
                    'low': 'text-orange-600',
                    'exhausted': 'text-red-600'
                };
                
                usageStatus.className = `text-xs ${statusColors[usageData.status] || 'text-blue-600'}`;
            }

            if (todayRequests) {
                todayRequests.textContent = `${usageData.requests_today} requests`;
            }

            if (usageBar) {
                const percentage = Math.min(usageData.usage_percentage, 100);
                usageBar.style.width = `${percentage}%`;
                
                const barColors = {
                    'healthy': 'bg-green-500',
                    'moderate': 'bg-yellow-500',
                    'low': 'bg-orange-500',
                    'exhausted': 'bg-red-500'
                };
                
                usageBar.className = `${barColors[usageData.status] || 'bg-blue-600'} h-2 rounded-full transition-all duration-300`;
            }
        }

        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function openAIAssistant() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                AI Content Assistant
                            </h2>
                            <button onclick="closeAIAssistant()" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- AI Usage Widget -->
                        <div id="ai-usage-widget-modal" class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-blue-900 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        AI Credits
                                    </h3>
                                    <p class="text-sm text-blue-700">Loading usage data...</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-900" id="credits-display-modal">$100.00</div>
                                    <div class="text-xs text-blue-600" id="usage-status-modal">Healthy</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-blue-600 mb-1">
                                    <span>Usage Today</span>
                                    <span id="today-requests-modal">0 requests</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div id="usage-bar-modal" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button class="p-4 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:shadow-lg transition-all">
                                <h3 class="font-semibold">✨ Improve Grammar</h3>
                                <p class="text-sm opacity-90">Fix grammar and spelling errors</p>
                            </button>
                            <button class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg hover:shadow-lg transition-all">
                                <h3 class="font-semibold">🎯 SEO Optimization</h3>
                                <p class="text-sm opacity-90">Optimize for search engines</p>
                            </button>
                            <button class="p-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg transition-all">
                                <h3 class="font-semibold">📖 Improve Readability</h3>
                                <p class="text-sm opacity-90">Make content easier to read</p>
                            </button>
                            <button class="p-4 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg hover:shadow-lg transition-all">
                                <h3 class="font-semibold">📊 Content Analysis</h3>
                                <p class="text-sm opacity-90">Get detailed content insights</p>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeAIAssistant();
                }
            });
            
            setTimeout(() => {
                loadAIUsageData();
            }, 100);
        }

        function closeAIAssistant() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                modal.remove();
            }
        }

        // Load default data on page load
        window.addEventListener('load', () => {
            setTimeout(() => {
                showDefaultUsageData();
            }, 500);
        });
    </script>
</body>
</html>
