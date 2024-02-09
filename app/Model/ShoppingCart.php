<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $primaryKey = 'identifier';
    protected $table = 'shoppingcart';
    public $incrementing = false;

    protected $fillable = [
        'identifier',
        'instance',
        'content',
        'email_sent',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'shoppingcart', 'identifier', 'identifier')->with('events');
    }
}
