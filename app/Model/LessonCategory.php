<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
}
