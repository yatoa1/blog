<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function store(Request $request)
    {
        // 验证输入数据
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        // 创建新的文章
        $article = Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => $request->user()->id 
        ]);

        return response()->json($article, 201);
    }
}
