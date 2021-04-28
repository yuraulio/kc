<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'priority', 'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'vimeo_video', 'vimeo_duration','author_id', 'creator_id'
    ];
}
