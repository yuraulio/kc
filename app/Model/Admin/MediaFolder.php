<?php

namespace App\Model\Admin;

use App\Model\User;
use App\Traits\SearchFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MediaFolder extends Model
{
    use HasFactory;
    use SearchFilter;

    protected $table = 'cms_folders';
    public $asYouType = true;
    protected $appends = ['size'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(MediaFolder::class, 'parent_id', 'id')->orderBy('order');
    }

    public function childrenAll()
    {
        return $this->children()->with('childrenAll');
    }

    public function parent()
    {
        return $this->belongsTo(MediaFolder::class, 'parent_id');
    }

    public function getSizeAttribute()
    {
        $file_size = $this->mediaFiles()->sum('size') * 0.000001;
        foreach ($this->children()->get() as $child) {
            $file_size += $child->mediaFiles()->sum('size') * 0.000001;
        }

        return number_format($file_size);
    }
}
