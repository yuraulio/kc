<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Model;

use App\Model\Topic;
use App\Model\Dropbox;
use App\Model\Event;
use App\Model\Lesson;
use App\Model\Testimonial;
use App\Model\Faq;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'parent', 'hours'];

    /**
     * Get all of the events that are assigned this tag.
     */

    public function events()
    {
        return $this->morphedByMany(Event::class, 'categoryable');
    }


    /**
     * Get all of the topics that are assigned this tag.
     */

    public function topics()
    {
        return $this->morphedByMany(Topic::class, 'categoryable');
    }


    public function tickets()
    {
        return $this->morphedByMany(Ticket::class, 'categoryable');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_tickets');
    }

    public function topic()
    {
        return $this->belongsToMany(Topic::class, 'categories_topics_lesson', 'category_id', 'topic_id');
    }

    public function dropbox()
    {
        return $this->belongsToMany(Dropbox::class, 'dropboxcache_category', 'category_id', 'dropbox_cache_category_id');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'categories_topics_lesson');
    }

    /**
     * Get all of the testimonials that are assigned this tag.
     */

    public function testimonials()
    {
        return $this->morphedByMany(Testimonial::class, 'categoryable');
    }

    /**
     * Get all of the faqs that are assigned this tag.
     */

    public function faqs()
    {
        return $this->morphedByMany(Faqs::class, 'categoryable');
    }

}
