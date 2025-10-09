@extends('layouts.admin')

@section('title', 'Create Category')
@section('subtitle', 'Add a new content category')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6">
            <h1 class="text-2xl font-semibold mb-6 transition-colors duration-300"
                :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Create Category</h1>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    @include('admin.categories._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection