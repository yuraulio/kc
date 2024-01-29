<?php

namespace App\Model\Admin;

use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use CodexShaper\Menu\Models\Menu;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Page extends Model implements Auditable
{
    use HasFactory;
    use SearchFilter;
    use Sluggable;
    use PaginateTable;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cms_pages';
    public $asYouType = true;

    public const VERSIONS = [
        [
            'instructors-testimonials',
            470,
            470,
        ],
        [
            'event-card',
            542,
            291,
        ],
        [
            'users',
            470,
            470,
        ],
        [
            'header-image',
            2880,
            1248,
        ],
        [
            'instructors-small',
            90,
            90,
        ],
        [
            'feed-image',
            300,
            300,
        ],
        // [
        //     "social-media-sharing",
        //     1920,
        //     832
        // ],
        // [
        //     "blog-content",
        //     680,
        //     320
        // ],
        // [
        //     "blog-featured",
        //     343,
        //     193
        // ]
    ];

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

        static::addGlobalScope('knowledge', function (Builder $builder) {
            $builder->where('type', '!=', 'Knowledge')->orWhere('type', null);
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'id'    => $this->id,
            'title'    => $this->title,
        ];

        return $array;
    }

    public function files()
    {
        return $this->belongsToMany(MediaFile::class, 'cms_link_pages_files', 'page_id', 'file_id');
    }

    public function category()
    {
        $pageType = PageType::whereTitle($this->type)->first();
        $pageType_id = $pageType ? $pageType->id : null;
        $category = Category::where('page_type_id', $pageType_id)->with('subcategories')->get();

        return $category;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_categories', 'page_id', 'category_id')->whereNull('parent_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Category::class, 'cms_link_pages_categories', 'page_id', 'category_id')->whereNotNull('parent_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
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
            ],
        ];
    }

    public function metaData()
    {
        $content = json_decode($this->content);
        foreach ($content as $row) {
            foreach ($row->columns as $column) {
                if ($column->component == 'meta') {
                    $feature_data = [];
                    foreach ($column->template->inputs as $input) {
                        $feature_data[$input->key] = $input->value ?? '';
                    }

                    return $feature_data;
                }
            }
        }

        return [
            'feature_title' => $this->title,
            'feature_description' => '',
            'feature_image' => '',
        ];
    }

    public function getMenu($id)
    {
        $menu = Menu::find($id);

        return [
            'name' => $menu->name ?? '',
            'title' => $menu->custom_class ?? '',
            'mobile' => $menu->url ?? '',
        ];
    }

    public function getTitle()
    {
        $content = json_decode($this->content);
        foreach ($content as $row) {
            foreach ($row->columns as $column) {
                if ($column->component == 'blog_header') {
                    return $column->template->inputs[0]->value;
                }
            }
        }

    }
}
