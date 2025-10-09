@extends('layouts.admin')

@section('title', 'Create New Post')
@section('subtitle', 'Write and publish a new blog post')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="rounded-lg shadow-sm overflow-hidden transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
        <div class="p-6 border-b transition-colors duration-300"
             :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">

                <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.posts._form', ['item' => new \App\Models\Post])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection