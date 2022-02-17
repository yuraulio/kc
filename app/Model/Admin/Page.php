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

    public function getInstructorAvatar()
    {
        $slug = Slug::whereSlug($this->slug)->first();
        $page = $slug->slugable;
        $data['content'] = $page;
        $instructor = Instructor::with('eventInstructorPage.category', 'mediable', 'eventInstructorPage.lessons', 'eventInstructorPage.slugable', 'eventInstructorPage.city', 'eventInstructorPage.summary1')->where('status', 1)->find($page['id']);
        $data['title'] = '';
        if (isset($instructor['title'])) {
            $data['title'] .= $instructor['title'];
        }
        if (isset($instructor['subtitle'])) {
            $data['title'] .= ' '.$instructor['subtitle'];
        }
        $data['title'] = trim($data['title']);
        return $data;
    }

    public function getInstructorCourses()
    {
        $slug = Slug::whereSlug($this->slug)->first();
        $page = $slug->slugable;
        $data['content'] = $page;
        $instructor = Instructor::with('eventInstructorPage.category', 'mediable', 'eventInstructorPage.lessons', 'eventInstructorPage.slugable', 'eventInstructorPage.city', 'eventInstructorPage.summary1')->where('status', 1)->find($page['id']);
        
        foreach ($instructor['eventInstructorPage'] as $key => $event) {
            if (($event['status'] == 0 || $event['status'] == 2) && $event->is_inclass_course()) {
                foreach ($event['lessons'] as $lesson) {
                    if ($lesson->pivot['date'] != '') {
                        $date = date("Y/m/d", strtotime($lesson->pivot['date']));
                    } else {
                        $date = date("Y/m/d", strtotime($lesson->pivot['time_starts']));
                    }
                    if (strtotime("now") < strtotime($date)) {
                        if ($lesson['instructor_id'] == $page['id']) {
                            $lessons[] = $lesson['title'];
                        }
                    }
                }
            }
        }

        $category = array();

        foreach ($instructor['eventInstructorPage'] as $key => $event) {
            if ($key == 0) {
                $category[$event['id']] = $event;
            } else {
                if (!isset($category[$event['id']])) {
                    $category[$event['id']] = $event;
                }
            }
        }

        $new_events = array();

        foreach ($category as $category) {
            if (count($new_events) == 0) {
                array_push($new_events, $category);
            } else {
                $find = false;
                foreach ($new_events as $event) {
                    if ($event['title'] == $category['title']) {
                        $find = true;
                    }
                }
                if (!$find) {
                    array_push($new_events, $category);
                }
            }
        }

        $data['instructorTeaches'] = array_unique($lessons);
        $data['instructorEvents'] = $new_events;

        return $data;
    }
}
