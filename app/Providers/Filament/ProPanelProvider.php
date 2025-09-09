<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentView;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ProPanelProvider extends PanelProvider
{
    /**
     * @throws \Exception
     */
    public function panel(Panel $panel): Panel
    {

        $panel
            ->id('pro')
            ->profile()
            ->defaultThemeMode(ThemeMode::Dark)
            ->spa(true)
            ->topNavigation((bool)request()->cookie('topNavigation'))
            ->brandName("System Admin")
            ->brandLogo(asset('/img/admin.png'))
            ->favicon(asset('/favicon.ico'))
            ->brandLogoHeight('5rem')
            ->unsavedChangesAlerts()
            //->databaseNotifications()
            ->navigationGroups([
                __('nav.shop'),
                __('nav.customer'),
                __('nav.blog'),
                __('nav.content'),
                __('nav.themes'),
                __('nav.settings'),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('nav.home'))
                    ->url(fn(): string => '/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-c-arrow-top-right-on-square'),
                // ...
            ])
            ->sidebarWidth('14.5rem')
            ->maxContentWidth(MaxWidth::Full)
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters');


        return $panel
            ->default()
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {

        $this->renderAssetDefault();
        Model::unguard();
    }

    public function renderAssetDefault(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn() => Vite::useBuildDirectory('assets')
                ->withEntryPoints(['resources/css/app.css'])->toHtml()
        );
    }
}
