<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActionLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'book_id',
        'user_id',
        'action',
    ];

}
