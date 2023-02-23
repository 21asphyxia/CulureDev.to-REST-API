<?php

namespace App\Models;

use Illuminate\Cache\TagSet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'published_at',
        // 'user_id',
        'category_id',
        // 'tag_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class , 'article_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class , 'article_tags');
    }
}
