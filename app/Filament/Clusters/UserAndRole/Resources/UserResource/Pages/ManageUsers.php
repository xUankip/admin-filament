<?php

namespace App\Filament\Clusters\UserAndRole\Resources\UserResource\Pages;

use App\Filament\Clusters\UserAndRole\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
