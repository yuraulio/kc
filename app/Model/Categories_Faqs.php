<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Faq;

class Categories_Faqs extends Model
{
    use HasFactory;

    protected $table = 'categories_faqs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public function faqs()
    {
        return $this->belongsToMany(Faq::class, 'faqs_categoryfaqs','faqs_categoryfaqs', 'faq_id');
    }
}
