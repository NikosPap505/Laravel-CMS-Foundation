<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-text-secondary">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $item->name ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>
<div class="mb-4">
    <label for="link" class="block text-sm font-medium text-text-secondary">Link (e.g., /about-us)</label>
    <input type="text" name="link" id="link" value="{{ old('link', $item->link ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>

{{-- Checkboxes for location --}}
<div class="space-y-2 mt-4">
    <label class="block text-sm font-medium text-text-secondary">Display Location</label>
    <div class="flex items-center">
        <input type="checkbox" name="show_in_header" id="show_in_header" value="1" @checked(old('show_in_header', $item->show_in_header ?? true)) class="rounded border-border bg-background text-primary shadow-sm focus:ring-accent">
        <label for="show_in_header" class="ml-2 text-sm text-text-primary">Show in Header</label>
    </div>
    <div class="flex items-center">
        <input type="checkbox" name="show_in_footer" id="show_in_footer" value="1" @checked(old('show_in_footer', $item->show_in_footer ?? false)) class="rounded border-border bg-background text-primary shadow-sm focus:ring-accent">
        <label for="show_in_footer" class="ml-2 text-sm text-text-primary">Show in Footer</label>
    </div>
</div>

<div class="mt-4">
    <label for="order" class="block text-sm font-medium text-text-secondary">Order</label>
    <input type="number" name="order" id="order" value="{{ old('order', $item->order ?? 0) }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" required>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.menu-items.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
    <button type="submit" class="btn-primary">Save</button>
</div>
