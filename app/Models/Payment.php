<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'from_user',
        'to_user',
        'amount',
        'registered_at',
        'description',
        'is_processed',
    ];
}
