<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CategoryPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'post_id',
    ];
}
