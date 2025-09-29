<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::orderBy('order', 'asc')->paginate(15);
        return view('admin.menu_items.index', compact('menuItems'));
    }

    public function create()
    {
        return view('admin.menu_items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        // Handle checkboxes
        $validated['show_in_header'] = $request->has('show_in_header');
        $validated['show_in_footer'] = $request->has('show_in_footer');

        MenuItem::create($validated);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item created successfully.');
    }

    public function edit(MenuItem $menuItem)
    {
        return view('admin.menu_items.edit', compact('menuItem'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        // Handle checkboxes
        $validated['show_in_header'] = $request->has('show_in_header');
        $validated['show_in_footer'] = $request->has('show_in_footer');

        $menuItem->update($validated);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item deleted successfully.');
    }

    // ✅ ADD THIS ENTIRE METHOD
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:menu_items,id'
        ]);

        foreach ($request->order as $index => $itemId) {
            MenuItem::where('id', $itemId)->update(['order' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }
}