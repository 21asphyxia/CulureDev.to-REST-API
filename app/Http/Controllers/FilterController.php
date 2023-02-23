<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class FilterController extends Controller
{
    public function filter(Request $request){
        $articl_query = Article::with(['user','category','tag']);

        if ($request->category) {
            $articl_query->whereHas('category', function($articles) use($request){
                $articles->where('name', $request->category);
            });
        }
    
        if ($request->tag) {
            $articl_query->whereHas('tag', function ($articles) use ($request) {
                $articles->where('name', $request->tag);
            });
        }
    
        $articles = $articl_query->get();
        return response()->json([
            'data'=>$articles,
        ], 200);
    }
}
