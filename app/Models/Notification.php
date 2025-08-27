<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'url',
        'meta',
        'is_read',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_read' => 'boolean',
    ];

    // Optional: Notification belongs to a user
    public function user(){
        return $this->belongsTo(Engineer::class, 'user_id');
    }
}
