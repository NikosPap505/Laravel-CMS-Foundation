<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'order',
        'parent_id',
        'show_in_header',
        'show_in_footer',
    ];

    protected $casts = [
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
    ];
}