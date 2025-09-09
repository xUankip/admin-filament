<?php

namespace App\Filament\Clusters\UserAndRole\Resources\UserResource\Actions;
use Filament\Tables;

class EditAction extends Action
{
    public static function make(): Tables\Actions\Action
    {
        return Tables\Actions\EditAction::make()
            ->iconButton()
            ->tooltip(trans('user.resource.title.edit'));
    }
}
