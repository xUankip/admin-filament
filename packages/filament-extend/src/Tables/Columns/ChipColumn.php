<?php

namespace Wiz\FilamentExtend\Tables\Columns;
use Filament\Tables\Columns\TextColumn;

class ChipColumn extends TextColumn
{
    protected string $view = 'filament-addons::tables.columns.chip-column';

    protected bool $invert = false;

    public function invert(bool $condition = true): static
    {
        $this->invert = $condition;

        return $this;
    }

    public function getInvert(): bool
    {
        return $this->invert;
    }
}
