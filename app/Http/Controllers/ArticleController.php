<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $articles = Article::orderBy('id')->get();
        $articles = Article::with('tags')->orderBy('id')->get();

        return new ArticleCollection($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        
        $article = Article::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'user_id'     => auth()->user()->id,

]);

        $tags = $request->tags;
        $tag_ids = [];

        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $tag_model = Tag::firstOrCreate(['name' => $tag]);
                $tag_ids[] = $tag_model->id;
            }
        }

        $article->tags()->sync($tag_ids);
        // Debugging statements
        error_log('Tags: ' . print_r($tags, true));
        error_log('Tag IDs: ' . print_r($tag_ids, true));
        error_log('Article Tags: ' . print_r($article->tags, true));

        return response()->json([
            'status'  => true,
            'message' => "Article Created successfully!",
            'article' => $article
        ], 201);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $article->find($article->id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(StoreArticleRequest $request, Article $article)
    {
        $article->update($request->all());

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Article Updated successfully!",
            'article' => $article
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Article deleted successfully'
        ], 200);
    }
}
