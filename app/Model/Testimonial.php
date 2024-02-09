<?php

namespace App\Model;

use App\Model\Category;
use App\Model\Instructor;
use App\Model\Media;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    use MediaTrait;

    protected $table = 'testimonials';

    protected $fillable = [
        'status', 'title', 'name', 'lastname', 'testimonial', 'video_url', 'social_url',
    ];

    public function category()
    {
        return $this->morphedByMany(Category::class, 'testimoniable');
    }

    public function instructors()
    {
        return $this->morphedByMany(Instructor::class, 'testimoniable');
    }

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
