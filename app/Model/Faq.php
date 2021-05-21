<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Category;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'title', 'answer', 'priority'
    ];

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
}
