<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Events\ArticleCreatedEvent;
use App\Models\Article;
use App\Models\User;

use App\Http\Controllers\V1\ArticleController as V1ArticleController;
use App\Http\Controllers\V2\ArticleController as V2ArticleController;

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ArticleAttachmentController;
use App\Http\Middleware\EnsureUserIsAdmin;

/*
|--------------------------------------------------------------------------
| API Routes - TechNova Project
|--------------------------------------------------------------------------
*/

// --- مسارات المصادقة والوسوم العامة ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/tags', [TagController::class, 'index']);



Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/articles', [V1ArticleController::class, 'index']);
    Route::get('/articles/{id}', [V1ArticleController::class, 'show']);
    Route::put('/articles/{id}', [V1ArticleController::class, 'update']);
    Route::post('/comments', [CommentController::class, 'store']);
});

Route::prefix('v2')->group(function () {
    Route::get('/articles', [V2ArticleController::class, 'index']);
});


Route::get('/test-queue', function () {
    $article = Article::first();
    $user = User::first();

    event(new ArticleCreatedEvent($article, $user));

    return response()->json(['message' => 'Event dispatched, check your queue!']);
});


// --- المسارات المحمية (Authenticated Routes) ---
Route::middleware('auth:sanctum')->group(function () {

    // إضافة تعليق عام
    Route::post('/comments', [CommentController::class, 'store']);
    
    Route::post('articles/{article}/attachments', [ArticleAttachmentController::class, 'store'])->name('articles.attachments.store');
    // لوحة تحكم الكُتّاب
    Route::prefix('writer')->name('writer.')->group(function () {
        Route::post('/articles', [\App\Http\Controllers\Writer\ArticleController::class, 'store']);
    });

    // لوحة تحكم الإدارة العليا (Admin Dashboard)
    Route::prefix('admin')->name('admin.')->middleware(EnsureUserIsAdmin::class)->group(function () {
        
        Route::get('/dashboard/stats', [DashboardController::class, 'index']);
        
        Route::get('/articles', [AdminArticleController::class, 'index']);
        Route::patch('/articles/{id}/status', [AdminArticleController::class, 'changeStatus']);
        
        Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy']);

        Route::get('/users', [AdminUserController::class, 'index']);
        Route::patch('/users/{id}/role', [AdminUserController::class, 'changeRole']);
        
        Route::post('articles/{article}/attachments', [ArticleAttachmentController::class, 'store']);
        Route::delete('/comments/{id}', [AdminCommentController::class, 'destroy']);
    });
  
});