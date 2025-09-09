<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class UserAndRole extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-m-user-group';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'auth';

    public static function getNavigationGroup(): ?string
    {
        return __('nav.settings');
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('nav.user_role');
    }
    public static function getNavigationLabel(): string
    {
        return __('nav.user');
    }
}
