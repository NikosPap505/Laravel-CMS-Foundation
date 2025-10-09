@extends('layouts.admin')

@section('title', 'Create New Page')
@section('subtitle', 'Add a new static page to your website')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6">
            <h1 class="text-2xl font-semibold mb-6 transition-colors duration-300"
                :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Create New Page</h1>

                <form action="{{ route('admin.pages.store') }}" method="POST">
                    @csrf
                    @include('admin.pages._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection