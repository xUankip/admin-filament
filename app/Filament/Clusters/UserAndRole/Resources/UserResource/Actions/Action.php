<?php

namespace App\Filament\Clusters\UserAndRole\Resources\UserResource\Actions;

abstract class Action
{
    abstract public static function make(): \Filament\Tables\Actions\Action;
}
