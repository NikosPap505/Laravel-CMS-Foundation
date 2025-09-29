<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-text-secondary">Name</label>
    <input type="text" name="name" id="title" value="{{ old('name', $item->name ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>
<div class="mb-4">
    <label for="slug" class="block text-sm font-medium text-text-secondary">Slug</label>
    <input type="text" name="slug" id="slug" value="{{ old('slug', $item->slug ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>
<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
    <button type="submit" class="btn-primary">Save</button>
</div>