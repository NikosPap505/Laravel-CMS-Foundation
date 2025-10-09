@extends('layouts.admin')

@section('title', 'User Management')
@section('subtitle', 'Manage system users and permissions')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-text-primary">Users</h1>
    <div class="flex space-x-4">
        <a href="{{ route('register') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-surface text-text-secondary border border-border rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-border">
            Register Page
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            Add New User
        </a>
    </div>
</div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Roles</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user) }}" class="link-edit">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection