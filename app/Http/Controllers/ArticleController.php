<?php

namespace App\Http\Controllers;

use DB;
use App\{Article,Category};
use Illuminate\Http\Request;

class ArticleController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ResponseWithSuccess(Article::with('categories')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $article = Article::create(request(['title', 'content']));
        foreach ($request->categories as $categoryId) {
            $category = Category::find($categoryId);
            if (! empty($category))
                $article->categories()->attach($categoryId);
            else
            {
                DB::rollBack();
                return $this->ResponseWithError("There is no category with id: " . $categoryId);
            }
        }
        DB::commit();

        return $this->ResponseWithSuccess($article->load('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return $this->ResponseWithSuccess($article->load('categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        DB::beginTransaction();
        $params = $request->only(['title', 'content']);

        foreach ($params as $param => $value) {
            $article->{$param} = $value;
        }

        if ($request->has('categories'))
        {
            $article->categories()->detach();

            foreach ($request->categories as $categoryId) {
                $category = Category::find($categoryId);
                if (! empty($category))
                    $article->categories()->attach($categoryId);
                else
                {
                    DB::rollBack();
                    return $this->ResponseWithError("There is no category with id: " . $categoryId);
                }
            }
        }
        DB::commit();

        return $this->ResponseWithSuccess($article->load('categories'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->load('categories');
        $article->categories()->detach();
        $article->delete();

        return $this->ResponseWithSuccess($article);
    }
}
