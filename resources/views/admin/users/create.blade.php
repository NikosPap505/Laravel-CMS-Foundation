@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-text-primary mb-6">Add New User</h1>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    @include('admin.users._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection