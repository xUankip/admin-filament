<?php

namespace Wiz\FilamentExtend;

use Filament\Facades\Filament;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WizFilamentExtendProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('wiz-filament-extend')
            ->hasViews('wiz-filament-extend')
            ->hasAssets()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::registerCssVariables([
            'wiz-flag-image' => __DIR__ . '/../resources/css/flagSprite42.png',
        ]);

        FilamentAsset::register([
            Css::make('wiz-filament-countries-with-flags', __DIR__ . '/../resources/css/wiz-filament-countries-with-flags.css'),
        ], 'wiz');

        FilamentAsset::register([
            Css::make('filament-forms-range-component', __DIR__ . '/../resources/css/filament-forms-range-component.css'),
        ], 'wiz');

    }
}
