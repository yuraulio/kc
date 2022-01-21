<?php

namespace App\Model\Admin;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;

class Category extends Model
{
    use HasFactory;
    use SearchFilter;

    protected $table = 'cms_categories';
    public $asYouType = true;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'cms_link_pages_categories', 'category_id', 'page_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, "parent_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
