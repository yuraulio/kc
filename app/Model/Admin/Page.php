<?php

namespace App\Model\Admin;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Traits\SlugTrait;

class Page extends Model
{
    use HasFactory;
    use SearchFilter;
    use SlugTrait;

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
        $array =  [
            'id'    => $this->id,
            'title'    => $this->title,
            'description' => $this->description
        ];

        return $array;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_categories', 'page_id', 'category_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_subcategories', 'page_id', 'category_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
