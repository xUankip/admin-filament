<?php

namespace Wiz\Helper\Traits;

use App\Models\User;

/**
 * @method static hasUser($userId=null)
 * @method hasUser($userId=null)
 */
trait HasUser
{
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeHasUser($query, $userId = null): void
    {
        if ($userId == null) {
            $userId = auth()->id();
        }
        $query->whereUserId($userId);
    }
}
