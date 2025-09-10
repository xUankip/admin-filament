<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'co_organizers' => 'array',
        'approval_log' => 'array',
        'status' => EventStatus::class,
    ];

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function organizer(): BelongsTo { return $this->belongsTo(User::class, 'organizer_id'); }

    public function registrations(): HasMany { return $this->hasMany(Registration::class); }
    public function feedback(): HasMany { return $this->hasMany(Feedback::class); }
    public function media(): HasMany { return $this->hasMany(Media::class, 'event_id'); }
}



