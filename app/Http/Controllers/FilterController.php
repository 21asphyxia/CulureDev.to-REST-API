<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class FilterController extends Controller
{
    public function filter(Request $request){
        $articl_query = Article::with(['user','category']);

        if ($request->has('category_id')) {
            $articl_query->where('category_id', $request->category_id);
        }
    
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $articl_query->whereHas('tags', function ($articl_query) use ($tags) {
                $articl_query->whereIn('name', $tags);
            });
        }
    
        $articles = $articl_query->get();
        return response()->json([
            'data'=>$articles,
        ], 200);
    }
}
