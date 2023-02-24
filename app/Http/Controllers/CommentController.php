<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }
    
    public function index()
    {
        $comments = Comment::all();

        return response()->json([
            "status" => "success",
            "comments" => $comments], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Comment Created successfully!",
            'comment' => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response()->json([
            'status' => true,
            'comment' => $comment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Comment Updated successfully!",
            'comment' => $comment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => "Comment Deleted successfully!"
        ], 200);
    }
}
