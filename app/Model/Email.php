<?php

namespace App\Model;

use App\Model\EmailTrigger;
use App\Model\MessageCategory;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Email extends Model
{
    use HasFactory,
        Sortable,
        Filterable,
        Searchable;

    protected $fillable = [
        'title',
        'status',
        'description',
        'template',
        'predefined_trigger',
        'creator_id',
        'filter_criteria',
    ];

    protected $casts = [
        'filter_criteria' => 'array',
        'template' => 'array',
    ];

    protected $appends = ['categories'];

    public function getCategoriesAttribute()
    {
        return $this->messaging_categories->map(function ($item) {
            return [
                'id'=>$item->id,
                'label'=>$item->title,
            ];
        });
    }

    public function messaging_categories(): MorphToMany
    {
        return $this->morphToMany(MessageCategory::class, 'messaging_categoryables');
    }

    public function triggers(): HasMany
    {
        return $this->hasMany(EmailTrigger::class);
    }
}
