@extends('layouts.admin')

@section('title', 'Edit Menu Item')
@section('subtitle', 'Update navigation menu item')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6">
                <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.menu_items._form', ['item' => $menuItem])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection