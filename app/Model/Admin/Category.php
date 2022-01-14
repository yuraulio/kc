<?php

namespace App\Model\Admin;

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

    /**
     * Get pages for category.
     */
    public function pages()
    {
        return $this->hasMany(Page::class, "category_id");
    }
}
