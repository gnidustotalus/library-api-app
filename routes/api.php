<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/auth/user', [AuthController::class, 'user'])->name('auth.user');
});

/*
|--------------------------------------------------------------------------
| Public Library API Routes
|--------------------------------------------------------------------------
*/

// Authors API
Route::prefix('authors')->name('api.authors.')->group(function () {
    Route::get('/', [AuthorController::class, 'index'])->name('index');
    Route::get('/{author}', [AuthorController::class, 'show'])->name('show');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [AuthorController::class, 'store'])->name('store');
        Route::put('/{author}', [AuthorController::class, 'update'])->name('update');
        Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
    });
});

// Publishers API
Route::prefix('publishers')->name('api.publishers.')->group(function () {
    Route::get('/', [PublisherController::class, 'index'])->name('index');
    Route::get('/{publisher}', [PublisherController::class, 'show'])->name('show');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [PublisherController::class, 'store'])->name('store');
        Route::put('/{publisher}', [PublisherController::class, 'update'])->name('update');
        Route::delete('/{publisher}', [PublisherController::class, 'destroy'])->name('destroy');
    });
});

// Categories API
Route::prefix('categories')->name('api.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
});

// Books API
Route::prefix('books')->name('api.books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{book}', [BookController::class, 'show'])->name('show');
    Route::get('/lists/popular', [BookController::class, 'popular'])->name('popular');
    Route::post('/search', [BookController::class, 'search'])->name('search');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::put('/{book}', [BookController::class, 'update'])->name('update');
        Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
    });
});
