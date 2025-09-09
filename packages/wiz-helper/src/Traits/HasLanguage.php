<?php

namespace Wiz\Helper\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder|Model language()
 */
trait HasLanguage
{
    /**
     * Scope a query with language.
     */
    public function scopeLanguage(Builder $query): void
    {
        $query->where('language', app()->getLocale());
    }
}
