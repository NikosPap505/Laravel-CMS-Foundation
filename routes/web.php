<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('pages', PageController::class);
});
