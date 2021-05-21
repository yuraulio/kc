<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Category;

class Testimonial extends Model
{
    use HasFactory;
    protected $table = 'testimonials';

    protected $fillable = [
        'status', 'title', 'name', 'testimonial'
    ];


    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
}
