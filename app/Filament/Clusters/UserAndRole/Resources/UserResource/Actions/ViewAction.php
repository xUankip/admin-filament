<?php

namespace App\Filament\Clusters\UserAndRole\Resources\UserResource\Actions;
use Filament\Tables;

class ViewAction extends Action
{
    public static function make(): Tables\Actions\Action
    {
        return Tables\Actions\ViewAction::make()
            ->iconButton()
            ->tooltip(trans('user.resource.title.show'));
    }
}
