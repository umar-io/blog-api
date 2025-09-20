<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
    ];

    protected $hidden = ['user_id']; //using resources would be a better approach

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
