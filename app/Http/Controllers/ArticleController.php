<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    // 通过隐式路由模型绑定的方式
    public function index()
    {
        return Article::all();
    }

    public function show(Article $article)
    {
        // 1.隐式路由普通方式
        // return $article;

        // auth-jwt篇：使用 API Resource 方式
        // return new ArticleResource($article);

        // auth-jwt篇：使用 API Resource 并兼容 JSON:API 方式
        ArticleResource::withoutWrapping();
        return new ArticleResource($article);

    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }

/*
 *  普通路由方式
    public function index()
    {
        return Article::all();
    }

    public function show($id)
    {
        return Article::find($id);
    }

    public function store(Request $request)
    {
        return Article::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return 204;
    }
*/
}
