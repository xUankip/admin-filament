<?php

namespace Wiz\FilamentExtend\Forms\Components;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Wiz\FilamentExtend\Traits\HasFormInputExt;


class WizMoneyInput extends TextInput
{
    function isVNDV1(): static
    {
        $this->mask(RawJs::make(<<<'JS'
                                    $money($input, '.', ',', 0)
                                    JS
        ))
            ->suffix('VNĐ');

        return $this;
    }

    function isVND(): static
    {
        $this->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 0)
            ->suffix('VNĐ');

        return $this;
    }
}
