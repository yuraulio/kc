<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const NEW_PAGES = 'new';
    const OLD_PAGES = 'old';

    protected $table = 'cms_general_settings';
}
