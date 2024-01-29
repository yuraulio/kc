<?php

namespace App\Model;

use App\Model\Event;
use App\Model\Faq;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesFaqs extends Model
{
    use HasFactory;

    protected $table = 'faqcategories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public function faqs()
    {
        return $this->morphToMany(Faq::class, 'faqable');
    }

    /*public function events()
    {
        return $this->morphedMany(Event::class, 'categoryfaqables');
    }*/
}
