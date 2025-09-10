<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-c-cog';

    protected static ?int $navigationSort = 9999;

    protected static ?string $slug = 'settings';


    public static function getNavigationGroup(): ?string
    {
        return __('nav.settings');
    }

    public static function getClusterBreadcrumb(): string
    {
      return __('nav.settings');
    }
    public static function getNavigationLabel(): string
    {
      return __('nav.settings');
    }
}
