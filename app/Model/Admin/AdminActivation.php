<?php

namespace App\Model\Admin;

use App\Model\Admin\Admin;
use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivation extends Model
{
    use HasFactory;

    protected $table = 'admin_activations';

    protected $fillable = [
        'user_id',
        'code',
        'completed',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }
}
