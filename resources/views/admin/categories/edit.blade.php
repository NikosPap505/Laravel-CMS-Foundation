@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-text-primary mb-6">Edit Category</h1>
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