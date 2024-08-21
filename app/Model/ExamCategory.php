<?php

namespace App\Model;

use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{
    protected $guarded = ['id'];
    use HasFactory,
        Sortable,
        Filterable,
        Searchable;
}
