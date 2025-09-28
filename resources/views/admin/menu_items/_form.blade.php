@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $item->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>

<div class="mb-4">
    <label for="link" class="block text-sm font-medium text-gray-700">Link (e.g., /about-us)</label>
    <input type="text" name="link" id="link" value="{{ old('link', $item->link ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>

<div class="mb-4">
    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
    <input type="number" name="order" id="order" value="{{ old('order', $item->order ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.menu-items.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
        Save
    </button>
</div>