<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function show(Page $page)
    {
        return view('page.show', compact('page'));
    }
}