<?php

namespace App\Model\Admin;

use App\Model\User;
use App\Traits\SearchFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class MediaFile extends Model implements Auditable
{
    use HasFactory;
    use SearchFilter;
    use \OwenIt\Auditing\Auditable;
    // use SoftDeletes;

    protected $table = 'cms_files';
    public $asYouType = true;

    protected $fillable = [
        'name',
        'extension',
        'path',
        'url',
        'extension',
        'size',
        'folder_id',
        'parent_id',
        'user_id',
        'full_path',
        'alt_text',
        'version',
        'link',
        'height',
        'width',
        'crop_data',
    ];

    protected $casts = [
        'crop_data' => 'array',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function mediaFolder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function subfiles()
    {
        return $this->hasMany(MediaFile::class, 'parent_id', 'id');
    }

    public function parrent()
    {
        return $this->belongsTo(MediaFile::class, 'parent_id', 'id');
    }

    public function siblings()
    {
        return $this->hasMany(MediaFile::class, 'parent_id', 'parent_id');
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'cms_link_pages_files', 'file_id', 'page_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'image_id', 'id');
    }
}
