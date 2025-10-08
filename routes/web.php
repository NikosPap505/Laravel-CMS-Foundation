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
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\AIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Custom Home Page Route
Route::get('/', function () {
    $homePage = Page::where('slug', 'home')->first();
    
    // Get recent posts for the homepage
    $posts = \App\Models\Post::with(['category', 'featuredImage'])
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->limit(3)
        ->get();
    
    return view('home', ['page' => $homePage, 'posts' => $posts]);
})->name('home');

// Features Page
Route::get('/features', function () {
    return view('features');
})->name('features');

// Admin Dashboard
Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('pages', PageController::class);
        Route::post('pages/reorder', [PageController::class, 'reorder'])->name('pages.reorder');
        
        Route::resource('menu-items', MenuItemController::class);
        Route::post('menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');

        Route::resource('categories', CategoryController::class)->middleware('permission:manage categories');
        Route::resource('posts', AdminPostController::class)->middleware('permission:manage posts');
        Route::post('posts/bulk-action', [AdminPostController::class, 'bulkAction'])->name('posts.bulk')->middleware('permission:manage posts');
        Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->middleware('permission:manage posts');
        Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->middleware('permission:manage posts');
        Route::post('comments/{comment}/approve', [\App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::post('comments/{comment}/reject', [\App\Http\Controllers\Admin\CommentController::class, 'reject'])->name('comments.reject');
        Route::post('comments/{comment}/spam', [\App\Http\Controllers\Admin\CommentController::class, 'markAsSpam'])->name('comments.spam');
        Route::post('comments/bulk-action', [\App\Http\Controllers\Admin\CommentController::class, 'bulkAction'])->name('comments.bulk');
        Route::post('autosave', [\App\Http\Controllers\Admin\AutoSaveController::class, 'store'])->name('autosave.store');
        Route::get('autosave', [\App\Http\Controllers\Admin\AutoSaveController::class, 'load'])->name('autosave.load');
        Route::delete('autosave', [\App\Http\Controllers\Admin\AutoSaveController::class, 'clear'])->name('autosave.clear');
        Route::resource('media', MediaController::class);
        
        Route::post('upload-image', [ImageUploadController::class, 'store'])->name('images.upload')->middleware('throttle.uploads');
        
        Route::resource('users', UserController::class)->middleware('role:admin');
        
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index')->middleware('role:admin');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store')->middleware('role:admin');

        // API routes (with rate limiting)
        Route::get('/api/media', [\App\Http\Controllers\Admin\MediaController::class, 'apiIndex'])
            ->name('api.media.index')
            ->middleware('throttle:60,1'); // 60 requests per minute
        
        // AI Assistant Routes
        Route::prefix('ai')->name('ai.')->middleware('permission:manage posts')->group(function () {
            Route::get('/', [AIController::class, 'index'])->name('index');
            Route::post('/generate-blog-post', [AIController::class, 'generateBlogPost'])->name('generate-blog-post');
            Route::post('/generate-meta-description', [AIController::class, 'generateMetaDescription'])->name('generate-meta-description');
            Route::post('/generate-titles', [AIController::class, 'generateTitles'])->name('generate-titles');
            Route::post('/generate-tags', [AIController::class, 'generateTags'])->name('generate-tags');
            Route::post('/improve-content', [AIController::class, 'improveContent'])->name('improve-content');
            Route::post('/analyze-content', [AIController::class, 'analyzeContent'])->name('analyze-content');
            Route::post('/generate-social-post', [AIController::class, 'generateSocialPost'])->name('generate-social-post');
            Route::get('/status', [AIController::class, 'status'])->name('status');
        });
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
Route::post('/blog/{post:slug}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
Route::get('/blog/{post:slug}/comments', [\App\Http\Controllers\CommentController::class, 'loadComments'])->name('comments.load');
Route::get('/feed', [PostController::class, 'feed'])->name('blog.feed');

// Contact Form Routes
Route::get('/contact', [ContactFormController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'store'])->name('contact.store')->middleware('throttle.forms');
Route::get('/contact/thank-you', function () {
    return view('contact.thank-you');
})->name('contact.thank-you');
Route::post('/newsletter-subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe')->middleware('throttle.forms');


// Authentication routes (e.g., /login)
require __DIR__.'/auth.php';



// Public Page Route (must always be last)
Route::get('/{page:slug}', [PublicPageController::class, 'show'])->name('page.show');
