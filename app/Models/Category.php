<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->belongsToMany(Post::class);
        // return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    protected $fillable = [
        'name',
    ];
}
