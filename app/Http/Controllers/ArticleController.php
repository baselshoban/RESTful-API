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
        $data = $this->paginate(Article::with('categories'));
        return $this->ResponseWithSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'categories' => 'required|array',
                'categories.*' => 'distinct|exists:categories,id'
            ]);

        DB::beginTransaction();

        // Create article with it's associated categories
        $article = Article::create(request(['title', 'content']));
        $article->categories()->attach($request->categories);

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
        // Validate
        $request->validate([
                'title' => 'max:255',
                'categories' => 'array|min:1',
                'categories.*' => 'distinct|exists:categories,id'
            ]);

        DB::beginTransaction();

        // Update parameters
        $params = $request->only(['title', 'content']);
        foreach ($params as $param => $value) {
            $article->{$param} = $value;
        }
        $article->save();

        // Update associated categories
        if ($request->has('categories'))
        {
            $article->categories()->detach();
            $article->categories()->attach($request->categories);
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
