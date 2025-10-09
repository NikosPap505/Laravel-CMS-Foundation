@extends('layouts.admin')

@section('title', 'Edit Page')
@section('subtitle', 'Update page content and settings')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6">
                <form action="{{ route('admin.pages.update', $page) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.pages._form', ['item' => $page])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection