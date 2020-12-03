<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleStoreRequest;
use App\Http\Resources\ArticleResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $data = ArticleResource::collection(Article::paginate((int)$request->get('limit')??10));

        return $this->success($data);
    }

    public function show(Article $article)
    {
        return $this->success(new ArticleResource($article));
    }

    public function store(ArticleStoreRequest $request)
    {
        Article::create($request->all());

        return $this->message('添加成功');
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return $this->message('修改成功');
    }

    public function delete(Article $article)
    {
        $article->delete();

        return $this->message('删除成功');
    }
}
