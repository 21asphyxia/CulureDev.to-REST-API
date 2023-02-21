<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'name',
    ];

    /**
     * Get the posts that belong to the tag.
     */

    public function articles(){
        return $this->belongsToMany(Post::class);
    }
}
