@if ($errors->any())
<div class="bg-error/20 border border-error text-error px-4 py-3 rounded-md relative mb-4">
    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

{{-- Define the userRoles variable to safely handle both create and edit cases --}}
@php
    $userRoles = isset($user) ? $user->roles->pluck('name')->toArray() : [];
@endphp

<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-text-secondary">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>
<div class="mb-4">
    <label for="email" class="block text-sm font-medium text-text-secondary">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>

{{-- Only show password fields on the create form --}}
@unless(isset($user))
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-text-secondary">Password</label>
        <input type="password" name="password" id="password" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
    </div>
    <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-text-secondary">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
    </div>
@endunless

<div class="mb-4">
    <label for="roles" class="block text-sm font-medium text-text-secondary">Roles</label>
    <select name="roles[]" id="roles" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" multiple required>
        @foreach($roles as $role)
            <option value="{{ $role->name }}" @selected(in_array($role->name, old('roles', $userRoles)))>{{ $role->name }}</option>
        @endforeach
    </select>
</div>
<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
    <button type="submit" class="btn-primary">Save User</button>
</div>