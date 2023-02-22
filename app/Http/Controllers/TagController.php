<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();

        return response()->json([
            "status" => "success",
            "tags" => $tags], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Tag Created successfully!",
            'Tag' => $tag
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return response()->json([
            'status' => true,
            'tag' => $tag
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Tag Updated successfully!",
            'Tag' => $tag
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json([
            'status' => true,
            'message' => "Tag Deleted successfully!"
        ], 200);
    }
}
