<?php

namespace App\Filament\Clusters\UserAndRole\Resources\UserResource\Actions;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends Action
{
    public static function make(): Tables\Actions\Action
    {
        return Tables\Actions\DeleteAction::make()
            ->using(function (Model $record, Tables\Actions\Action $action) {
                self::checkIfLastUserOrCurrentUser($record, $action);
            })
            ->iconButton()
            ->tooltip(trans('user.resource.title.delete'));
    }

    private static function checkIfLastUserOrCurrentUser(Model $record, Tables\Actions\Action $action): void
    {
        $count = User::query()->count();
        if ($count === 1) {
            Notification::make()
                ->title(trans('user.resource.notificaitons.last.title'))
                ->body(trans('user.resource.notificaitons.last.body'))
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->send();

            return;
        } elseif (auth()->user()->id === $record->id) {
            Notification::make()
                ->title(trans('user.resource.notificaitons.self.title'))
                ->body(trans('user.resource.notificaitons.self.body'))
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->send();

            return;
        } else {
            $record->delete();
            $action->success();
        }
    }
}
