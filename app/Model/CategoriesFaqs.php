<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Faq;
use App\Model\Event;

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
        return $this->morphToMany(Faq::class,'faqable');
    }


    /*public function events()
    {
        return $this->morphedMany(Event::class, 'categoryfaqables');
    }*/

   
}
