<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
{
    $pages = Page::orderBy('order', 'asc')->get();
    return view('admin.pages.index', compact('pages'));
}

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'content' => 'nullable|string',
        ]);

        Page::create($validated);

        // ΔΙΟΡΘΩΣΗ ΕΔΩ: Προστέθηκε το 'admin.'
        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $page->update($validated);

        // ΔΙΟΡΘΩΣΗ ΕΔΩ: Προστέθηκε το 'admin.'
        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        // ΔΙΟΡΘΩΣΗ ΕΔΩ: Προστέθηκε το 'admin.'
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully!');
    }

    // ✅ ADD THIS ENTIRE METHOD
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:pages,id'
        ]);

        foreach ($request->order as $index => $pageId) {
            Page::where('id', $pageId)->update(['order' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }
}