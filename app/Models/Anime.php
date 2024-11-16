<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    // Define your fillable properties
    protected $fillable = [
        'mal_id',
        'title',
        'slug',
        'synopsis',
        'image_url',
    ];
}
