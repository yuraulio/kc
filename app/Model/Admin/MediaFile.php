<?php

namespace App\Model\Admin;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFile extends Model
{
    use HasFactory;
    use SearchFilter;
    use SoftDeletes;

    protected $table = 'cms_files';
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

    public function mediaFolder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function subfiles()
    {
        return $this->hasMany(MediaFile::class, "parent_id", "id");
    }
}
