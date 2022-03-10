<?php

namespace App\Model\Admin;

use App\Model\Instructor;
use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Traits\SlugTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use CodexShaper\Menu\Models\Menu;
use App\Model\Slug;

class Page extends Model
{
    use HasFactory;
    use SearchFilter;
    use Sluggable;

    protected $table = 'cms_pages';
    public $asYouType = true;
    protected $with = ['categories'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->wherePublished(true);
        });
    }

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
        ];

        return $array;
    }

    public function files()
    {
        return $this->belongsToMany(MediaFile::class, 'cms_link_pages_files', 'page_id', 'file_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_categories', 'page_id', 'category_id')->whereNull("parent_id");
    }

    public function subcategories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_categories', 'page_id', 'category_id')->whereNotNull("parent_id");
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, "user_id");
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

    public function metaData()
    {
        $content = json_decode($this->content);
        foreach ($content as $row) {
            foreach ($row->columns as $column) {
                if ($column->component == "meta") {
                    $feature_data = [];
                    foreach ($column->template->inputs as $input) {
                        $feature_data[$input->key] = $input->value ?? "";
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

    public function getMenu($id)
    {
        $menu = Menu::find($id);
        return [
            'name' => $menu->name ?? "",
            'title' => $menu->custom_class ?? "",
        ];
    }
}
