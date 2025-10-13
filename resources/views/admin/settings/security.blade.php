@extends('layouts.admin')

@section('title', 'Security Settings')

@php
    // Calculate security score based on key settings
    $securityChecks = [
        'two_factor_enabled' => setting('security.two_factor_enabled', false),
        'strong_passwords' => setting('security.password_min_length', 8) >= 8,
        'login_protection' => setting('security.failed_login_lockout', false),
        'captcha_enabled' => setting('security.captcha_enabled', false),
    ];
    
    $totalChecks = count($securityChecks);
    $passedChecks = array_sum($securityChecks);
    $percentage = $totalChecks > 0 ? round(($passedChecks / $totalChecks) * 100) : 0;
    
    // Determine security level and color
    $securityLevel = 'Needs Improvement';
    $colorClass = 'bg-red-500';
    
    if ($percentage >= 90) {
        $securityLevel = 'Excellent';
        $colorClass = 'bg-green-500';
    } elseif ($percentage >= 60) {
        $securityLevel = 'Good';
        $colorClass = 'bg-yellow-500';
    }
@endphp
@section('subtitle', 'Security and authentication options')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center text-sm font-medium text-text-secondary hover:text-accent">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Settings
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-text-secondary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Security Settings</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.settings.store.security') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- Two-Factor Authentication --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Two-Factor Authentication</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" value="1" 
                                           {{ old('two_factor_enabled', setting('security.two_factor_enabled', false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="two_factor_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable Two-Factor Authentication
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Require users to use 2FA for additional security</p>
                            </div>
                        </div>

                        {{-- Password Requirements --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Password Requirements</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password_min_length" class="block text-sm font-medium text-text-secondary mb-2">Minimum Password Length</label>
                                    <input type="number" name="password_min_length" id="password_min_length" 
                                           value="{{ old('password_min_length', setting('security.password_min_length', 8)) }}" 
                                           min="6" max="50"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Minimum number of characters required</p>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="password_require_special" id="password_require_special" value="1" 
                                               {{ old('password_require_special', setting('security.password_require_special', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="password_require_special" class="ml-2 block text-sm text-text-primary">
                                            Require Special Characters
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="password_require_numbers" id="password_require_numbers" value="1" 
                                               {{ old('password_require_numbers', setting('security.password_require_numbers', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="password_require_numbers" class="ml-2 block text-sm text-text-primary">
                                            Require Numbers
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Session Management --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Session Management</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="session_timeout" class="block text-sm font-medium text-text-secondary mb-2">Session Timeout (minutes)</label>
                                    <input type="number" name="session_timeout" id="session_timeout" 
                                           value="{{ old('session_timeout', setting('security.session_timeout', 120)) }}" 
                                           min="5" max="1440"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">How long users stay logged in (1-1440 minutes)</p>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="failed_login_lockout" id="failed_login_lockout" value="1" 
                                               {{ old('failed_login_lockout', setting('security.failed_login_lockout', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="failed_login_lockout" class="ml-2 block text-sm text-text-primary">
                                            Enable Login Lockout
                                        </label>
                                    </div>

                                    <div>
                                        <label for="failed_login_attempts" class="block text-sm font-medium text-text-secondary mb-2">Max Failed Attempts</label>
                                        <input type="number" name="failed_login_attempts" id="failed_login_attempts" 
                                               value="{{ old('failed_login_attempts', setting('security.failed_login_attempts', 5)) }}" 
                                               min="3" max="10"
                                               class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <p class="mt-1 text-xs text-text-secondary">Number of failed attempts before lockout</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- IP Management --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">IP Management</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="ip_whitelist" class="block text-sm font-medium text-text-secondary mb-2">IP Whitelist</label>
                                    <textarea name="ip_whitelist" id="ip_whitelist" rows="4" 
                                              class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                              placeholder="192.168.1.1&#10;10.0.0.0/8&#10;172.16.0.0/12">{{ old('ip_whitelist', setting('security.ip_whitelist')) }}</textarea>
                                    <p class="mt-1 text-xs text-text-secondary">One IP address or CIDR range per line</p>
                                </div>

                                <div>
                                    <label for="ip_blacklist" class="block text-sm font-medium text-text-secondary mb-2">IP Blacklist</label>
                                    <textarea name="ip_blacklist" id="ip_blacklist" rows="4" 
                                              class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                              placeholder="192.168.1.100&#10;10.0.0.0/24">{{ old('ip_blacklist', setting('security.ip_blacklist')) }}</textarea>
                                    <p class="mt-1 text-xs text-text-secondary">Block these IP addresses from accessing the site</p>
                                </div>
                            </div>
                        </div>

                        {{-- CAPTCHA Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">CAPTCHA Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="captcha_enabled" id="captcha_enabled" value="1" 
                                           {{ old('captcha_enabled', setting('security.captcha_enabled', false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="captcha_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable CAPTCHA
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Requires reCAPTCHA configuration in Integration Settings</p>
                            </div>
                        </div>

                        {{-- Security Status --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Security Status</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <h4 class="font-medium text-text-primary">Current Security Level</h4>
                                        <div class="flex items-center">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                                                <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-sm text-text-secondary">{{ $securityLevel }}</span>
                                        </div>
                                        <p class="text-xs text-text-secondary">
                                            @if($percentage >= 90)
                                                Your site has excellent security settings
                                            @elseif($percentage >= 60)
                                                Your site has good security settings
                                            @else
                                                Your site needs security improvements
                                            @endif
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <h4 class="font-medium text-text-primary">Security Checklist</h4>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('security.two_factor_enabled') ? 'bg-green-400' : 'bg-yellow-400' }} mr-2"></div>
                                                <span class="text-text-secondary">2FA {{ setting('security.two_factor_enabled') ? 'Enabled' : 'Available' }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('security.password_min_length', 8) >= 8 ? 'bg-green-400' : 'bg-red-400' }} mr-2"></div>
                                                <span class="text-text-secondary">Strong Passwords</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('security.failed_login_lockout') ? 'bg-green-400' : 'bg-red-400' }} mr-2"></div>
                                                <span class="text-text-secondary">Login Protection</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('security.captcha_enabled') ? 'bg-green-400' : 'bg-yellow-400' }} mr-2"></div>
                                                <span class="text-text-secondary">CAPTCHA {{ setting('security.captcha_enabled') ? 'Enabled' : 'Available' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Security Recommendations --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Security Recommendations</h3>
                            <div class="bg-accent/10 border border-accent/20 rounded-lg p-4">
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Enable 2FA for all admin accounts to prevent unauthorized access.</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Use strong passwords with special characters and numbers.</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Regularly update your software and dependencies.</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Monitor failed login attempts and set up alerts.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-border pt-6">
                        <a href="{{ route('admin.settings.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Save Security Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
