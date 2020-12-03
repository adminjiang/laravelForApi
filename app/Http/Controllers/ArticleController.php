<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Article;
use Illuminate\Contracts\Support\Jsonable;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $data = ArticleResource::collection(Article::all());

        return $this->success($data);
    }

    public function show(Article $article)
    {
        return $this->success(new ArticleResource($article));
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
}
