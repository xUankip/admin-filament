<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'issued_on' => 'date',
    ];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
}


