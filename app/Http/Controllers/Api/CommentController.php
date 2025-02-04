<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller
{



    public function store(Request $request, Post $post , Article $article)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        \Log::info('Article ID: ' . $article->id); // 记录实际的文章ID

        // 验证输入数据
        $validated = $request->validate([
            'comment' => 'required|string|max:255'
        ]);

        // 创建评论
        $comment = Comment::create([
            'comment' => $validated['comment'],
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'article_id' => $article->id  
        ]);

        return response()->json($comment, 201);
    }
}
