<?php

namespace App\Model;

use App\Model\Email;
use App\Model\MobileNotification;
use App\Model\WebNotification;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageCategory extends Model
{
    use HasFactory,
        Sortable,
        Filterable,
        Searchable;

    protected $primaryKey = 'id';
    protected $table = 'messaging_categories';

    protected $fillable = [
        'title',
        'published',
        'description',
    ];

    public function email()
    {
        return $this->morphedByMany(Email::class, 'messaging_categoryables');
    }

    public function mobile_app_notification()
    {
        return $this->morphedByMany(MobileNotification::class, 'messaging_categoryables');
    }

    public function web_app_notification()
    {
        return $this->morphedByMany(WebNotification::class, 'messaging_categoryables');
    }
}
