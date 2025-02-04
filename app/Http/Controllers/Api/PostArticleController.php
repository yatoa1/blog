<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostArticleController extends Controller
{
    public function home()
    {
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();
        $articles = Article::with('user')->orderBy('created_at', 'desc')->get();

        // 合并并排序
        $allItems = $posts->merge($articles)->sortByDesc('created_at');

        return response()->json($allItems);
    }

    public function myPosts()
    {
        $userId = Auth::id(); // 获取当前登录用户的ID
        \Log::info('Fetching posts for user ID: ' . $userId); // 记录日志
        $posts = Auth::user()->posts;

        // Debug 输出
        if ($posts->isEmpty()) {
            \Log::warning('No posts found for user ID: ' . $userId);
        } else {
            \Log::info('Posts: ', ['data' => $posts->toArray()]);
        }

        return response()->json($posts);
    }

    public function myArticles()
    {
        $userId = Auth::id(); // 获取当前登录用户的ID
        \Log::info('Fetching articles for user ID: ' . $userId); // 记录日志
        $articles = Auth::user()->articles;

        // Debug 输出
        if ($articles->isEmpty()) {
            \Log::warning('No articles found for user ID: ' . $userId);
        } else {
            \Log::info('Articles: ', ['data' => $articles->toArray()]);
        }

        return response()->json($articles);
    }
    public function updateArticle(Request $request, $id)
    {
        $article = Auth::user()->articles()->find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->update($request->all());

        return response()->json(['message' => 'Article updated successfully']);
    }

    public function deleteArticle($id)
    {
        $article = Auth::user()->articles()->find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }

    public function updatePost(Request $request, $id)
    {
        // 验证请求数据
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        // 查找动态
        $post = Auth::user()->posts()->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // 更新动态
        $post->update($validated);

        return response()->json(['message' => 'Post updated successfully', 'data' => $post]);
    }

    public function deletePost($id)
    {
        // 查找动态
        $post = Auth::user()->posts()->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // 删除动态
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
