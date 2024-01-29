<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    use HasFactory;

    protected $table = 'activations';

    protected $fillable = [
        'user_id',
        'code',
        'completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
