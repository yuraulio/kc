<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\CategoriesFaqs;
use App\Model\Category;
use App\Model\Event;


class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'title', 'answer', 'priority'
    ];

    /*public function category()
    {
        return $this->belongsToMany(CategoriesFaqs::class, 'faq_categoryfaqs','faq_id','categoryfaq_id');
    }*/

    public function category()
    {
        return $this->morphedByMany(CategoriesFaqs::class, 'faqable')->withPivot('priority');
    }

    public function categoryEvent()
    {
        return $this->morphedByMany(Category::class, 'faqable')->withPivot('priority');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'faqable')->withPivot('priority');
    }
}
