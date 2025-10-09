@extends('layouts.admin')

@section('title', 'AI Analytics & Insights')
@section('subtitle', 'Deep dive into your AI usage patterns and optimization opportunities')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-8 h-8 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    AI Analytics & Insights
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Understand your AI usage patterns and optimize your content workflow</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="refreshAnalytics()" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
                <a href="{{ route('admin.ai.usage-page') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Basic Usage
                </a>
            </div>
        </div>

        <!-- Error Message -->
        @if(isset($error))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-medium">Analytics Unavailable</h3>
                    <p class="text-red-700 mt-1">{{ $error }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Optimization Tips -->
        <div id="optimization-tips" class="mb-8">
            <!-- Tips will be loaded here -->
        </div>

        <!-- Main Analytics Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Feature Usage Chart -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Feature Usage (Last 30 Days)
                </h3>
                <div id="feature-usage-chart" class="h-64 flex items-center justify-center">
                    <div class="text-center text-gray-600 dark:text-gray-300">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-accent mx-auto mb-2"></div>
                        <p>Loading feature usage data...</p>
                    </div>
                </div>
            </div>

            <!-- Success Rate Analysis -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Success Rate Analysis
                </h3>
                <div id="success-rate-analysis" class="space-y-4">
                    <!-- Success rate data will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Usage Trends -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Daily Usage Trend -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Daily Usage Trend
                </h3>
                <div id="daily-usage-chart" class="h-64 flex items-center justify-center">
                    <div class="text-center text-gray-600 dark:text-gray-300">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-accent mx-auto mb-2"></div>
                        <p>Loading daily usage data...</p>
                    </div>
                </div>
            </div>

            <!-- Peak Usage Hours -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Peak Usage Hours
                </h3>
                <div id="peak-usage-hours" class="space-y-3">
                    <!-- Peak usage data will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Cost Efficiency Analysis -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
                Cost Efficiency Analysis
            </h3>
            <div id="cost-efficiency-table" class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-300">Feature</th>
                            <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-300">Total Cost</th>
                            <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-300">Successful Requests</th>
                            <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-300">Cost per Success</th>
                            <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-300">Efficiency</th>
                        </tr>
                    </thead>
                    <tbody id="cost-efficiency-body">
                        <!-- Cost efficiency data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAdvancedAnalytics();
});

async function loadAdvancedAnalytics() {
    try {
        const response = await fetch('{{ route("admin.ai.analytics.data") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            updateAnalyticsDisplay(data.data);
        } else {
            showEmptyState();
        }
    } catch (error) {
        console.error('Failed to load analytics data:', error);
        showEmptyState();
    }
}

function updateAnalyticsDisplay(analyticsData) {
    // Update optimization tips
    updateOptimizationTips(analyticsData.optimization_tips);
    
    // Update feature usage chart
    updateFeatureUsageChart(analyticsData.feature_usage);
    
    // Update success rate analysis
    updateSuccessRateAnalysis(analyticsData.feature_usage);
    
    // Update daily usage chart
    updateDailyUsageChart(analyticsData.daily_usage);
    
    // Update peak usage hours
    updatePeakUsageHours(analyticsData.hourly_usage);
    
    // Update cost efficiency table
    updateCostEfficiencyTable(analyticsData.cost_efficiency);
}

function updateOptimizationTips(tips) {
    const container = document.getElementById('optimization-tips');
    
    if (!tips || tips.length === 0) {
        container.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-green-800 font-medium">Great AI Usage!</h3>
                        <p class="text-green-700 mt-1">Your AI usage patterns look optimal. Keep up the great work!</p>
                    </div>
                </div>
            </div>
        `;
        return;
    }

    let tipsHtml = '';
    tips.forEach(tip => {
        const priorityColors = {
            'high': 'red',
            'medium': 'yellow',
            'low': 'blue'
        };
        const color = priorityColors[tip.priority] || 'blue';
        
        tipsHtml += `
            <div class="bg-${color}-50 border border-${color}-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-${color}-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-${color}-800 font-medium">${tip.type.replace('_', ' ').toUpperCase()} Tip</h3>
                        <p class="text-${color}-700 mt-1">${tip.message}</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = tipsHtml;
}

function updateFeatureUsageChart(featureUsage) {
    const ctx = document.createElement('canvas');
    ctx.id = 'feature-usage-chart-canvas';
    document.getElementById('feature-usage-chart').innerHTML = '';
    document.getElementById('feature-usage-chart').appendChild(ctx);

    if (!featureUsage || featureUsage.length === 0) {
        document.getElementById('feature-usage-chart').innerHTML = `
            <div class="text-center text-gray-600 dark:text-gray-300 py-12">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p>No feature usage data available</p>
            </div>
        `;
        return;
    }

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: featureUsage.map(f => f.operation_type.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: featureUsage.map(f => f.total_requests),
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#D1D5DB' : '#6B7280',
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

function updateSuccessRateAnalysis(featureUsage) {
    const container = document.getElementById('success-rate-analysis');
    
    if (!featureUsage || featureUsage.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-600 dark:text-gray-300 py-8">
                <p>No success rate data available</p>
            </div>
        `;
        return;
    }

    let html = '';
    featureUsage.forEach(feature => {
        const successRate = feature.success_rate || 0;
        const color = successRate >= 70 ? 'green' : successRate >= 50 ? 'yellow' : 'red';
        
        html += `
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-${color}-100 rounded-lg flex items-center justify-center">
                        <span class="text-${color}-600 font-bold text-sm">${Math.round(successRate)}%</span>
                    </div>
                    <span class="text-gray-900 dark:text-white">${feature.operation_type.replace('_', ' ')}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-${color}-500 h-2 rounded-full" style="width: ${successRate}%"></div>
                    </div>
                    <span class="text-gray-600 dark:text-gray-300 text-sm">${feature.accepted_suggestions}/${feature.total_requests}</span>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updateDailyUsageChart(dailyUsage) {
    const ctx = document.createElement('canvas');
    ctx.id = 'daily-usage-chart-canvas';
    document.getElementById('daily-usage-chart').innerHTML = '';
    document.getElementById('daily-usage-chart').appendChild(ctx);

    if (!dailyUsage || dailyUsage.length === 0) {
        document.getElementById('daily-usage-chart').innerHTML = `
            <div class="text-center text-gray-600 dark:text-gray-300 py-12">
                <p>No daily usage data available</p>
            </div>
        `;
        return;
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyUsage.map(d => new Date(d.date).toLocaleDateString()),
            datasets: [{
                label: 'AI Requests',
                data: dailyUsage.map(d => d.requests),
                borderColor: '#8B5CF6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#D1D5DB' : '#6B7280'
                    },
                    grid: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#E5E7EB'
                    }
                },
                x: {
                    ticks: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#D1D5DB' : '#6B7280'
                    },
                    grid: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#E5E7EB'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#D1D5DB' : '#6B7280'
                    }
                }
            }
        }
    });
}

function updatePeakUsageHours(hourlyUsage) {
    const container = document.getElementById('peak-usage-hours');
    
    if (!hourlyUsage || hourlyUsage.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-600 dark:text-gray-300 py-8">
                <p>No hourly usage data available</p>
            </div>
        `;
        return;
    }

    const maxRequests = Math.max(...hourlyUsage.map(h => h.requests));
    let html = '';
    
    hourlyUsage.forEach(hour => {
        const percentage = (hour.requests / maxRequests) * 100;
        const timeLabel = hour.hour === 0 ? '12 AM' : hour.hour < 12 ? `${hour.hour} AM` : hour.hour === 12 ? '12 PM' : `${hour.hour - 12} PM`;
        
        html += `
            <div class="flex items-center justify-between">
                <span class="text-gray-900 dark:text-white text-sm">${timeLabel}</span>
                <div class="flex items-center space-x-2 flex-1 mx-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: ${percentage}%"></div>
                    </div>
                    <span class="text-gray-600 dark:text-gray-300 text-sm w-8">${hour.requests}</span>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updateCostEfficiencyTable(costEfficiency) {
    const tbody = document.getElementById('cost-efficiency-body');
    
    if (!costEfficiency || costEfficiency.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-600 dark:text-gray-300">
                    No cost efficiency data available
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    costEfficiency.forEach(feature => {
        const efficiency = feature.successful_requests > 0 ? 
            ((feature.successful_requests / (feature.total_cost / 0.001)) * 100) : 0;
        const efficiencyColor = efficiency >= 70 ? 'text-green-600' : efficiency >= 50 ? 'text-yellow-600' : 'text-red-600';
        
        html += `
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="py-3 px-4 text-gray-900 dark:text-white">${feature.operation_type.replace('_', ' ')}</td>
                <td class="py-3 px-4 text-gray-600 dark:text-gray-300">$${feature.total_cost.toFixed(4)}</td>
                <td class="py-3 px-4 text-gray-600 dark:text-gray-300">${feature.successful_requests}</td>
                <td class="py-3 px-4 text-gray-600 dark:text-gray-300">$${feature.cost_per_success.toFixed(4)}</td>
                <td class="py-3 px-4 ${efficiencyColor} font-medium">${Math.round(efficiency)}%</td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function showEmptyState() {
    document.getElementById('optimization-tips').innerHTML = `
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Analytics Data Yet</h3>
            <p class="text-gray-600 mb-4">Start using AI features to see detailed analytics and insights.</p>
            <a href="{{ route('admin.posts.create') }}" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-accent-hover transition">
                Start Creating Content
            </a>
        </div>
    `;
}

function refreshAnalytics() {
    document.getElementById('feature-usage-chart').innerHTML = `
        <div class="text-center text-gray-600 dark:text-gray-300">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-accent mx-auto mb-2"></div>
            <p>Refreshing analytics...</p>
        </div>
    `;
    loadAdvancedAnalytics();
}
</script>
@endpush
@endsection
