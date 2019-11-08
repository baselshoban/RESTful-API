<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
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
        $data = $this->paginate(Category::query());
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
        $request->validate([
                'name' => 'required|max:255',
            ]);
        $category = Category::create(request(['name']));

        return $this->ResponseWithSuccess($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->ResponseWithSuccess($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
                'name' => 'nullable|max:255',
            ]);
        
        if ($request->has('name')) {
            $category->name = $request->name;
            $category->save();
        }

        return $this->ResponseWithSuccess($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ( $category->hasAnyArticles() )
            return $this->ResponseWithError(
                $category->name . " category still have articles associated with it. You can't delete it."
            );
        $category->delete();

        return $this->ResponseWithSuccess($category);
    }
}
