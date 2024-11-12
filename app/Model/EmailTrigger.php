<?php

namespace App\Model;

use App\Model\Email;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTrigger extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'trigger_filters' => 'array',
    ];

    public function email()
    {
        return $this->belongsTo(Email::class);
    }
}
