<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController as PublicArticleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Events\ArticleCreatedEvent;
use App\Models\Article;
use App\Models\User;

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Middleware\EnsureUserIsAdmin;

// --- المسارات العامة ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/articles', [PublicArticleController::class, 'index']);
Route::get('/articles/{id}', [PublicArticleController::class, 'show']);
Route::get('/tags', [TagController::class, 'index']);

// --- مسار اختبار الطابور (خارج Middleware الحماية لتجنب خطأ Login) ---
Route::get('/test-queue', function () {
    $article = Article::first();
    $user = User::first();

    event(new ArticleCreatedEvent($article, $user));

    return response()->json(['message' => 'Event dispatched, check your queue!']);
});

// --- المسارات المحمية ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/comments', [CommentController::class, 'store']);

    Route::prefix('writer')->name('writer.')->group(function () {
        Route::post('/articles', [\App\Http\Controllers\Writer\ArticleController::class, 'store']);
    });

    Route::prefix('admin')->name('admin.')->middleware(EnsureUserIsAdmin::class)->group(function () {
        
        Route::get('/dashboard/stats', [DashboardController::class, 'index']);
        
        Route::get('/articles', [AdminArticleController::class, 'index']);
        Route::patch('/articles/{id}/status', [AdminArticleController::class, 'changeStatus']);
        
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::patch('/users/{id}/role', [AdminUserController::class, 'changeRole']);
        
        Route::delete('/comments/{id}', [AdminCommentController::class, 'destroy']);
    });
  
});