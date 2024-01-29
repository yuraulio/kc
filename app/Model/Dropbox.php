<?php

namespace App\Model;

use App\Model\Category;
use App\Model\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dropbox extends Model
{
    use HasFactory;
    protected $table = 'dropbox_cache';

    protected $casts = ['folders' => 'json', 'files' => 'json'];

    protected $fillable = [
        'folders', 'files', 'folder_name',
    ];

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'dropboxcacheable');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'dropboxcacheable');
    }
}
