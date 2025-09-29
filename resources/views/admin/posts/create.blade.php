@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 border-b border-border">
                <h1 class="text-2xl font-semibold text-text-primary mb-6">Create Post</h1>

                <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.posts._form', ['item' => new \App\Models\Post])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection