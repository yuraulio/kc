<?php

namespace App\Model;

use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
