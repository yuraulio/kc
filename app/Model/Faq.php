<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\CategoriesFaqs;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'title', 'answer', 'priority'
    ];

    public function category()
    {
        return $this->belongsToMany(CategoriesFaqs::class, 'faq_categoryfaqs','faq_id','categoryfaq_id');
    }
}
