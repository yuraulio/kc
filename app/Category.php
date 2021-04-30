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
namespace App;

use App\Model\Topic;
use App\Model\Event;
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
     * Get the items of the category
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // public function topics()
    // {
    //     return $this->belongsToMany(Topic::class, 'categories_topics_lesson');
    // }

    ///////////////////////NEW

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
