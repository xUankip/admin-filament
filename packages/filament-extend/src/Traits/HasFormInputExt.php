<?php

namespace Wiz\FilamentExtend\Traits;

use Illuminate\Support\Str;

trait HasFormInputExt
{
    function hintWithRemainChars($maxLength, $reactive = true): static
    {
        $this->hint(function (?string $state) use ($maxLength): string {
            return (string)Str::of(mb_strlen($state))
                ->append(' / ')
                ->append($maxLength . ' ')
                ->append(Str::of(__('characters'))->lower());
        });

        if ($reactive) {
            $this->reactive();
        }
        
        return $this;
    }
}
