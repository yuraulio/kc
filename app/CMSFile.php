<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CMSFile extends Model
{
    use HasFactory;

    protected $table = 'cms_files';

    /**
     * Get the comments for the blog post.
     */
    public function images(): HasMany
    {
        return $this->hasMany(CMSFile::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(CMSFile::class, 'parent_id');
    }
}
