<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media_gallery';

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploader_id'); }
}


