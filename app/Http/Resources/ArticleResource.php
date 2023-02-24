<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id' => $this->id,
        //     'user_id' => $this->user_id,
        //     'title' => $this->title,
        //     'content' => $this->content,
        //     'category' => new CategoryResource($this->category),
        //     'comment' => new CommentCollection($this->comments),
        //     'tags' => new TagCollection($this->tags)
        // ];
        return [
            'id'       => $this->id,
            'user_id'  => $this->user_id,
            'title'    => $this->title,
            'content'  => $this->content,
            'category' => new CategoryResource($this->category),
            'comment'  => new CommentCollection($this->comments),
            'tags'     => new TagCollection($this->whenLoaded('tags')),
        ];
    }
}
