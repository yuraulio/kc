<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;

class Page extends Model
{
    use HasFactory;
    use SearchFilter;

    protected $table = 'cms_pages';
    public $asYouType = true;
    protected $with = ['categories'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_categories', 'page_id', 'category_id');
    }
}
