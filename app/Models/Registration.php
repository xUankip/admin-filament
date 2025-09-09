<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fields_snapshot' => 'array',
        'private_thread' => 'array',
    ];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function attendance(): HasOne { return $this->hasOne(Attendance::class); }
}


