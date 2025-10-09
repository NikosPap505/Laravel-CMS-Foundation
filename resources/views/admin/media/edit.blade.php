@extends('layouts.admin')

@section('title', 'Edit Media')
@section('subtitle', 'Update media information and settings')

@section('content')

<div class="space-y-6">
    <!-- Edit Form -->
    <div class="rounded-lg shadow-sm border transition-colors duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="p-6 border-b transition-colors duration-300"
             :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
            <h1 class="text-2xl font-semibold mb-6 transition-colors duration-300"
                :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Edit Media</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <img src="{{ asset('storage/' . $medium->path) }}" alt="{{ $medium->alt_text }}" class="w-full rounded-lg border transition-colors duration-300"
                         :class="theme === 'dark' ? 'border-gray-600' : 'border-gray-300'">
                </div>
                <div class="md:col-span-2">
                    <form action="{{ route('admin.media.update', $medium) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div class="p-6 rounded-lg border transition-colors duration-300"
                                 :class="theme === 'dark' ? 'bg-gray-900 border-gray-700' : 'bg-gray-50 border-gray-200'">
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium transition-colors duration-300"
                                               :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $medium->name) }}" class="mt-1 block w-full rounded-md shadow-sm transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500"
                                               :class="theme === 'dark' ? 'bg-gray-800 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                    </div>
                                    <div>
                                        <label for="alt_text" class="block text-sm font-medium transition-colors duration-300"
                                               :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Alt Text</label>
                                        <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text', $medium->alt_text) }}" class="mt-1 block w-full rounded-md shadow-sm transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500"
                                               :class="theme === 'dark' ? 'bg-gray-800 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                    </div>
                                    <div>
                                        <label for="caption" class="block text-sm font-medium transition-colors duration-300"
                                               :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Caption</label>
                                        <textarea name="caption" id="caption" rows="3" class="mt-1 block w-full rounded-md shadow-sm transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500"
                                                  :class="theme === 'dark' ? 'bg-gray-800 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'">{{ old('caption', $medium->caption) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.media.index') }}" class="text-sm mr-4 transition-colors duration-300 hover:text-blue-600"
                                   :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Cancel</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">Update</button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-6 border-t pt-6 transition-colors duration-300"
                         :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                         <form action="{{ route('admin.media.destroy', $medium) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-300">Delete Media</button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
