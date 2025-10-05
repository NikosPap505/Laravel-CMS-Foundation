<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
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

    public function store(StorePageRequest $request)
    {
        $validated = $request->validated();
        Page::create($validated);
        
        // Clear cache when new page is created
        clear_cms_cache();

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $validated = $request->validated();
        $page->update($validated);
        
        // Clear cache when page is updated
        clear_cms_cache();

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        
        // Clear cache when page is deleted
        clear_cms_cache();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:pages,id'
        ]);

        foreach ($request->order as $index => $pageId) {
            Page::where('id', $pageId)->update(['order' => $index + 1]);
        }
        
        // Clear cache when pages are reordered
        clear_cms_cache();

        return response()->json(['status' => 'success']);
    }
}