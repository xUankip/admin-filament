<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $guarded = [];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    // Relationships
    public function event(): BelongsTo 
    { 
        return $this->belongsTo(Event::class); 
    }

    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }

    public function registration(): BelongsTo 
    { 
        return $this->belongsTo(Registration::class); 
    }
}



