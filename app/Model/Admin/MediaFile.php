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
        'admin_label',
        'extension',
        'path',
        'full_path',
        'url',
        'folder_id',
        'parent_id',
        'alt_text',
        'link',
        'size',
        'height',
        'width',
        'version',
        'user_id',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'admin_label' => $this->admin_label,
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
