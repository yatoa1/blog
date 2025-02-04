<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\API\PostArticleController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

use App\Models\Article;


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



//注册
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

//登录
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');



// 使用Sanctum中间件保护所有需要认证的API端点
Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    //个人资料
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    // 登出接口 - 需要认证
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    // 发布动态接口 - 需要认证
    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    // 发布文章接口 - 需要认证
    Route::post('/articles', [ArticleController::class, 'store']);

    //对动态评论
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comment.store');

    //对文章评论
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
        ->name('articles.store');

    //发表留言
    Route::post('/messages', [MessageController::class, 'store'])
        ->name('messages.store');

    //获取用户所有动态
    Route::get('/my-posts', [PostArticleController::class, 'myPosts'])
    ->name('my-posts');

    //获取用户所有文章
    Route::get('/my-articles', [PostArticleController::class, 'myArticles'])
    ->name('my-articles');

    //更新文章
    Route::put('/articles/{id}', [PostArticleController::class, 'updateArticle'])
    ->name('articles.updateArticle');

    //删除文章
    Route::delete('/articles/{id}', [PostArticleController::class, 'deleteArticle'])
    ->name('articles.deleteArticles');
    
    //修改动态
    Route::put('/posts/{id}', [PostArticleController::class, 'updatePost'])
    ->name('posts.updatePost');

    //删除动态
    Route::delete('/posts/{id}', [PostArticleController::class, 'deletePost'])
    ->name('posts.deletePost');
});


//首页展示所有信息
Route::get('/home', [PostArticleController::class, 'home']);


//展示所有留言
Route::get('/messages', [MessageController::class, 'index']);

// Route::post('/posts', [PostController::class, 'store'])
// ->name('posts.store');


// Route::post('/comments',[CommentController::class,'store'])
// ->name('comments.store');

// Route::post('/messages', [MessageController::class, 'store'])
// ->name('messages.store');
