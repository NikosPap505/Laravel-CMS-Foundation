<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;

Route::get('/', function () {
    return redirect('/admin/pages');
});

Route::prefix('admin')->group(function () {
    Route::resource('pages', PageController::class);
});

