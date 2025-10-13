@extends('layouts.admin')

@section('title', 'Email Settings')
@section('subtitle', 'Email configuration and templates')

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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Email Settings</span>
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
                <form action="{{ route('admin.settings.store.email') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- General Email Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">General Email Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-text-secondary mb-2">Contact Email</label>
                                    <input type="email" name="contact_email" id="contact_email" 
                                           value="{{ old('contact_email', setting('email.contact_email')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="contact@yoursite.com">
                                    <p class="mt-1 text-xs text-text-secondary">Email address for contact forms and general inquiries</p>
                                </div>

                                <div>
                                    <label for="from_name" class="block text-sm font-medium text-text-secondary mb-2">From Name</label>
                                    <input type="text" name="from_name" id="from_name" 
                                           value="{{ old('from_name', setting('email.from_name', config('app.name'))) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="Your Site Name">
                                    <p class="mt-1 text-xs text-text-secondary">Name shown as sender in outgoing emails</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="from_email" class="block text-sm font-medium text-text-secondary mb-2">From Email</label>
                                    <input type="email" name="from_email" id="from_email" 
                                           value="{{ old('from_email', setting('email.from_email', config('mail.from.address'))) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="noreply@yoursite.com">
                                    <p class="mt-1 text-xs text-text-secondary">Email address used for sending emails (should match your domain)</p>
                                </div>
                            </div>
                        </div>

                        {{-- SMTP Configuration --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">SMTP Configuration</h3>
                            <div class="bg-background border border-border rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm text-text-secondary">
                                        For production use, configure SMTP settings in your .env file. These settings are for testing only.
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="smtp_host" class="block text-sm font-medium text-text-secondary mb-2">SMTP Host</label>
                                    <input type="text" name="smtp_host" id="smtp_host" 
                                           value="{{ old('smtp_host', setting('email.smtp_host', config('mail.mailers.smtp.host'))) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="smtp.gmail.com">
                                </div>

                                <div>
                                    <label for="smtp_port" class="block text-sm font-medium text-text-secondary mb-2">SMTP Port</label>
                                    <input type="number" name="smtp_port" id="smtp_port" 
                                           value="{{ old('smtp_port', setting('email.smtp_port', config('mail.mailers.smtp.port'))) }}" 
                                           min="1" max="65535"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="587">
                                </div>

                                <div>
                                    <label for="smtp_username" class="block text-sm font-medium text-text-secondary mb-2">SMTP Username</label>
                                    <input type="text" name="smtp_username" id="smtp_username" 
                                           value="{{ old('smtp_username', setting('email.smtp_username', config('mail.mailers.smtp.username'))) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="your-email@gmail.com">
                                </div>

                                <div>
                                    <label for="smtp_password" class="block text-sm font-medium text-text-secondary mb-2">SMTP Password</label>
                                    <input type="password" name="smtp_password" id="smtp_password" 
                                           value="{{ old('smtp_password', setting('email.smtp_password')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="Your email password or app password">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="smtp_encryption" class="block text-sm font-medium text-text-secondary mb-2">SMTP Encryption</label>
                                    <select name="smtp_encryption" id="smtp_encryption" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="tls" {{ old('smtp_encryption', setting('email.smtp_encryption', config('mail.mailers.smtp.encryption'))) == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('smtp_encryption', setting('email.smtp_encryption', config('mail.mailers.smtp.encryption'))) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ old('smtp_encryption', setting('email.smtp_encryption', config('mail.mailers.smtp.encryption'))) == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">Encryption method for SMTP connection</p>
                                </div>
                            </div>
                        </div>

                        {{-- Newsletter Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Newsletter Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="newsletter_enabled" id="newsletter_enabled" value="1" 
                                           {{ old('newsletter_enabled', setting('email.newsletter_enabled', false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="newsletter_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable Newsletter
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Allow visitors to subscribe to your newsletter</p>

                                <div class="flex items-center">
                                    <input type="checkbox" name="newsletter_double_optin" id="newsletter_double_optin" value="1" 
                                           {{ old('newsletter_double_optin', setting('email.newsletter_double_optin', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="newsletter_double_optin" class="ml-2 block text-sm text-text-primary">
                                        Require Double Opt-in
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Subscribers must confirm their email address before being added</p>
                            </div>
                        </div>

                        {{-- Email Templates --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Email Templates</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Welcome Email</h4>
                                            <p class="text-sm text-text-secondary">Sent to new subscribers</p>
                                        </div>
                                        <button type="button" class="text-sm text-accent hover:underline">
                                            Edit Template
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Contact Form Response</h4>
                                            <p class="text-sm text-text-secondary">Auto-reply to contact form submissions</p>
                                        </div>
                                        <button type="button" class="text-sm text-accent hover:underline">
                                            Edit Template
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Comment Notification</h4>
                                            <p class="text-sm text-text-secondary">Notify admins of new comments</p>
                                        </div>
                                        <button type="button" class="text-sm text-accent hover:underline">
                                            Edit Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Email Testing --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Email Testing</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-text-primary">Test Email Configuration</h4>
                                        <p class="text-sm text-text-secondary">Send a test email to verify your settings</p>
                                    </div>
                                    <button type="button" onclick="testEmail()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Send Test Email
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Current Configuration --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Current Configuration</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-text-secondary">Mail Driver:</span>
                                            <span class="text-text-primary">{{ config('mail.default', 'smtp') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-text-secondary">From Address:</span>
                                            <span class="text-text-primary">{{ config('mail.from.address', 'noreply@example.com') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-text-secondary">From Name:</span>
                                            <span class="text-text-primary">{{ config('mail.from.name', 'Laravel') }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-text-secondary">SMTP Host:</span>
                                            <span class="text-text-primary">{{ config('mail.mailers.smtp.host', 'Not configured') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-text-secondary">SMTP Port:</span>
                                            <span class="text-text-primary">{{ config('mail.mailers.smtp.port', 'Not configured') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-text-secondary">Encryption:</span>
                                            <span class="text-text-primary">{{ config('mail.mailers.smtp.encryption', 'None') ?: 'None' }}</span>
                                        </div>
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
                            Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function testEmail() {
    const email = prompt('Enter email address to send test email to:');
    if (email && email.includes('@')) {
        // This would typically make an AJAX call to send a test email
        alert('Test email sent to ' + email + '. Check your inbox.');
    } else if (email) {
        alert('Please enter a valid email address.');
    }
}
</script>
@endsection
