<?php

use Illuminate\Support\Facades\Route;
use App\Models\Page;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\NewsletterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Custom Home Page Route
Route::get('/', function () {
    $homePage = Page::where('slug', 'home')->first();
    return view('home', ['page' => $homePage]);
})->name('home');

// Custom dashboard redirect
Route::get('/dashboard', function () {
    return redirect()->route('admin.pages.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('pages', PageController::class);
        Route::post('pages/reorder', [PageController::class, 'reorder'])->name('pages.reorder');
        
        Route::resource('menu-items', MenuItemController::class);
        Route::post('menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');

        Route::resource('categories', CategoryController::class);
        Route::resource('posts', AdminPostController::class);
        
        Route::post('upload-image', [ImageUploadController::class, 'store'])->name('images.upload');
        
        Route::resource('users', UserController::class)->middleware('role:admin');
        
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index')->middleware('role:admin');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store')->middleware('role:admin');
    });

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Public Routes ---

// Custom "About Us" page route
Route::get('/about-us', function () {
    $page = Page::where('slug', 'about-us')->firstOrFail();
    return view('about', ['page' => $page]);
})->name('about');

// Custom "Services" page route
Route::get('/services', function () {
    $page = Page::where('slug', 'services')->firstOrFail();
    return view('services', ['page' => $page]);
})->name('services');

// Public Blog Routes
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category:slug}', [PostController::class, 'category'])->name('blog.category');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');

// Contact Form Routes
Route::get('/contact', [ContactFormController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'store'])->name('contact.store');
Route::get('/contact/thank-you', function () {
    return view('contact.thank-you');
})->name('contact.thank-you');
Route::post('/newsletter-subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');


// Authentication routes (e.g., /login)
require __DIR__.'/auth.php';

// Public Page Route (must always be last)
Route::get('/{page:slug}', [PublicPageController::class, 'show'])->name('page.show');
