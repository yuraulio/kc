<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Category;
use App\Model\Instructor;

class Testimonial extends Model
{
    use HasFactory;
    protected $table = 'testimonials';

    protected $fillable = [
        'status', 'title', 'name','lastname' , 'testimonial', 'video_url', 'social_url'
    ];


    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function instructors()
    {
        return $this->morphedByMany(Instructor::class, 'testimoniable');
    }

}
