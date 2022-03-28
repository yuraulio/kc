<?php

namespace App\Model\Admin;

use App\Model\User;
use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    use SearchFilter;
    use PaginateTable;

    protected $table = 'cms_comments';

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function page()
    {
        return $this->belongsTo(Page::class, "page_id");
    }
}
