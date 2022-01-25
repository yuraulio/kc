<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use App\Model\User;

class Template extends Model
{
    use HasFactory;
    use SearchFilter;

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
        return $this->hasMany(Page::class, "template_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
