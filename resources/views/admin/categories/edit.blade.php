@extends('layouts.admin')

@section('title', 'Edit Category')
@section('subtitle', 'Update category information')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.categories._form', ['item' => $category])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection