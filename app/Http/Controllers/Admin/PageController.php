<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' =>'required|string|max:255|unique:pages',
            'content' => 'nullable|string',
        ]);

        // Only use 'title' since 'slug' column has been removed
        Page::create($request->only('title', 'content', 'slug'));

         return redirect()->route('admin.pages.index')->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
         $request->validate([
        'title' => 'required|string|max:255',
        'slug'  => 'required|string|max:255|unique:pages,slug,'.$page->id,
        'content' => 'nullable|string',
    ]);

        // Only update 'title' since 'slug' column has been removed
        
        $page->update($request->only('title','slug','content'));
        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully!');
    }
}
