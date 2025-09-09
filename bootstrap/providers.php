<?php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\ProPanelProvider::class,
];

/*
if(str_contains(request()->path(), 'admin') || app()->runningInConsole()){
    return [
        App\Providers\AppServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
    ];
}else{
    return [
        App\Providers\AppServiceProvider::class,
    ];
}*/


