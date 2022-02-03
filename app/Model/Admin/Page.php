<?php

namespace App\Model\Admin;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Traits\SlugTrait;
use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model
{
    use HasFactory;
    use SearchFilter;
    use Sluggable;

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
            'description' => $this->description,
            'categoryes' => $this->categories,
            'subcategories' => $this->subcategories,
            'template' => $this->template["title"],
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

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', "desc");
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => false,
            ]
        ];
    }

    public function featureData()
    {
        $content = json_decode($this->content);
        foreach ($content as $row) {
            foreach ($row->columns as $column) {
                if ($column->component == "post_feature") {
                    $feature_data = [];
                    foreach ($column->template->inputs as $input) {
                        $feature_data[$input->key] = $input->value;
                    }
                    return $feature_data;
                }
            }
        }
        return [
            "feature_title" => $this->title,
            "feature_description" => "",
            "feature_image" => "",
        ];
    }
}
