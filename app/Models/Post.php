<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'meta_title',
        'meta_description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}