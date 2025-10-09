@extends('layouts.admin')

@section('title', 'Add New User')
@section('subtitle', 'Create a new user account')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6">
            <h1 class="text-2xl font-semibold mb-6 transition-colors duration-300"
                :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Add New User</h1>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    @include('admin.users._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection