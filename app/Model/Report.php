<?php

namespace App\Model;

use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory,
        Sortable,
        Filterable,
        Searchable;

    protected $fillable = [
        'title',
        'date_range',
        'file_export_criteria',
        'description',
        'creator_id',
        'filter_criteria',
    ];

    protected $casts = [
        'filter_criteria' => 'array',
        'file_export_criteria' => 'array',
        'date_range' => 'array',
    ];
}
