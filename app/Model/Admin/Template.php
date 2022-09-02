<?php

namespace App\Model\Admin;

use CodexShaper\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Model\User;
use App\Traits\PaginateTable;

class Template extends Model
{
    use HasFactory;
    use SearchFilter;
    use PaginateTable;

    protected $table = 'cms_templates';
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
     * Get pages.
     */
    public function pages()
    {
        return $this->hasMany(Page::class, "template_id")->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, "user_id");
    }

    public function getMenu($id)
    {
        $menu = Menu::find($id);
        return [
            'name' => $menu->name ?? "",
            'title' => $menu->custom_class ?? "",
            'mobile' => $menu->url ?? "",
        ];
    }
}
