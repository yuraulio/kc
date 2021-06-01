<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Categories_Faqs;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'title', 'answer', 'priority'
    ];

    public function category()
    {
        return $this->belongsToMany(Categories_Faqs::class, 'faqs_categoryfaqs','faq_id', 'faqs_categoryfaqs');
    }
}
