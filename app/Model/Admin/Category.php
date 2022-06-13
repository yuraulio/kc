<?php

namespace App\Model\Admin;

use App\Model\User;
use App\Traits\PaginateTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;

class Category extends Model
{
    use HasFactory;
    use SearchFilter;
    use PaginateTable;

    protected $table = 'cms_categories';
    public $asYouType = true;
    protected $appends = ['pages_count'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['subcategoryes'] = $this->subcategories;

        return $array;
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'cms_link_pages_categories', 'category_id', 'page_id');
    }

    // public function subPages()
    // {
    //     return $this->belongsToMany(Page::class, 'cms_link_pages_categories', 'category_id', 'page_id');
    // }

    public function subcategories()
    {
        return $this->hasMany(Category::class, "parent_id");
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, "parent_id");
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, "user_id");
    }

    public function getPagesCountAttribute()
    {
        return $this->pages()->count();
    }

    public function image()
    {
        return $this->belongsTo(MediaFile::class, "image_id");
    }
}
