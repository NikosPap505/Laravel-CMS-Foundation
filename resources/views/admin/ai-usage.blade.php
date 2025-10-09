@extends('layouts.admin')

@section('title', 'AI Assistant Usage')
@section('subtitle', 'Track your AI usage and manage your credits')

@section('content')

<div class="max-w-4xl mx-auto">

        {{-- Main Usage Card --}}
        <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-2xl p-8 text-white mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Your AI Credits</h2>
                    <p class="text-purple-100">Available for content creation and optimization</p>
                </div>
                <div class="text-right">
                    <div class="text-5xl font-bold mb-2" id="main-credits">$100</div>
                    <div class="text-purple-200" id="main-status">Ready to help</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-2xl font-bold">0</div>
                    <div class="text-sm text-purple-200">Used Today</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-2xl font-bold">0</div>
                    <div class="text-sm text-purple-200">This Month</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-2xl font-bold">$0</div>
                    <div class="text-sm text-purple-200">Total Cost</div>
                </div>
            </div>
        </div>

        {{-- Usage Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Recent Usage --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Recent AI Operations</h3>
                <div class="space-y-3" id="recent-operations">
                    <div class="text-center text-text-secondary py-8">
                        <svg class="w-12 h-12 mx-auto mb-4 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <p>No AI operations yet</p>
                        <p class="text-sm mt-2">Start creating content to see your AI usage here</p>
                    </div>
                </div>
            </div>

            {{-- Usage by Feature --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Usage by Feature</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-text-primary">Title Generation</span>
                        </div>
                        <span class="text-text-secondary">0 uses</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-text-primary">SEO Optimization</span>
                        </div>
                        <span class="text-text-secondary">0 uses</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-text-primary">Content Improvement</span>
                        </div>
                        <span class="text-text-secondary">0 uses</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-text-primary">Tag Generation</span>
                        </div>
                        <span class="text-text-secondary">0 uses</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- How to Use AI --}}
        <div class="bg-surface p-6 rounded-lg border border-border shadow-lg mb-8">
            <h3 class="text-lg font-semibold text-text-primary mb-4">How to Use AI Assistant</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-blue-600 font-bold text-lg">1</span>
                    </div>
                    <h4 class="font-semibold text-text-primary mb-2">Create Content</h4>
                    <p class="text-sm text-text-secondary">Go to Posts → Create New Post to start writing</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-green-600 font-bold text-lg">2</span>
                    </div>
                    <h4 class="font-semibold text-text-primary mb-2">Use AI Tools</h4>
                    <p class="text-sm text-text-secondary">Click the AI Assistant button to access AI features</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-purple-600 font-bold text-lg">3</span>
                    </div>
                    <h4 class="font-semibold text-text-primary mb-2">Watch Credits</h4>
                    <p class="text-sm text-text-secondary">Your credits update automatically after each use</p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.posts.create') }}" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white p-6 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl flex items-center justify-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold text-lg">Start Writing with AI</span>
            </a>
            <a href="{{ route('admin.ai.index') }}" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white p-6 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl flex items-center justify-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span class="font-semibold text-lg">AI Assistant Tools</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAIUsageData();
});

async function loadAIUsageData() {
    try {
        const response = await fetch('{{ route("admin.ai.usage") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            updateUsageDisplay(data.data);
        } else {
            showDefaultUsage();
        }
    } catch (error) {
        console.error('Failed to load AI usage data:', error);
        showDefaultUsage();
    }
}

function showDefaultUsage() {
    const defaultData = {
        credits_remaining: 100.00,
        requests_today: 0,
        requests_this_month: 0,
        estimated_cost: 0,
        status: 'healthy'
    };
    updateUsageDisplay(defaultData);
}

function updateUsageDisplay(usageData) {
    // Update main credits display
    const mainCredits = document.getElementById('main-credits');
    const mainStatus = document.getElementById('main-status');
    
    if (mainCredits) {
        mainCredits.textContent = `$${usageData.credits_remaining.toFixed(0)}`;
    }
    
    if (mainStatus) {
        const statusTexts = {
            'healthy': 'Ready to help',
            'moderate': 'Getting busy',
            'low': 'Almost full',
            'exhausted': 'Need more credits'
        };
        mainStatus.textContent = statusTexts[usageData.status] || 'Ready to help';
    }
    
    // Update usage breakdown
    const todayElement = document.querySelector('.grid .bg-white\\/10:nth-child(1) .text-2xl');
    const monthElement = document.querySelector('.grid .bg-white\\/10:nth-child(2) .text-2xl');
    const costElement = document.querySelector('.grid .bg-white\\/10:nth-child(3) .text-2xl');
    
    if (todayElement) todayElement.textContent = usageData.requests_today || 0;
    if (monthElement) monthElement.textContent = usageData.requests_this_month || 0;
    if (costElement) costElement.textContent = `$${usageData.estimated_cost.toFixed(2)}`;
}
</script>
@endpush
@endsection
