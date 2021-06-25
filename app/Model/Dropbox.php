<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Category;

class Dropbox extends Model
{
    use HasFactory;
    protected $table = 'dropbox_cache';

    protected $casts = ['folders' => 'json', 'files' => 'json'];

    protected $fillable = [
        'folders', 'files', 'folder_name'
    ];

     public function categories()
     {
         return $this->morphedByMany(Category::class, 'dropboxcacheable');
     }
}
