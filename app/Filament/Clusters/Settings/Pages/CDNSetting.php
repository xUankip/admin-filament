<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\ConfigEnum;
use App\Filament\Clusters\Settings;
use App\Models\System\ZiConfig;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Rawilk\FilamentPasswordInput\Password;

class CDNSetting extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-c-photo';
    protected static ?string $cluster = Settings::class;
    protected static string $view = 'pages.settings.base-setting';
    protected static ?string $slug = 'cdn';
    protected static ?int $navigationSort = 25;

    public ?array $data = [];


    public function getTitle(): string|Htmlable
    {
        return __('setting_cdn.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('nav.cdn_setting');
    }


    public function mount(): void
    {
        $config = ZiConfig::getConfigAndParserValue(ConfigEnum::CDN_SETTING);
        $this->form->fill($config);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Section::make(__('setting_cdn.amazon_title'))->schema([

                    Toggle::make('amazon.enabled')
                        ->label(__('setting_cdn.amazon_enabled'))
                        ->default(false), // Mặc định bật

                    TextInput::make('amazon.access_key')
                        ->label(__('setting_cdn.amazon_access_key'))
                        ->helperText(__('setting_cdn.amazon_access_key_helper')),

                    TextInput::make('amazon.secret_key')
                        ->label(__('setting_cdn.amazon_secret_key'))
                        ->helperText(__('setting_cdn.amazon_secret_key_helper')),

                    TextInput::make('amazon.region')
                        ->label(__('setting_cdn.amazon_region'))
                        ->helperText(__('setting_cdn.amazon_region_helper')),

                    Textarea::make('amazon.notes')
                        ->label(__('setting_cdn.amazon_notes'))
                        ->placeholder(__('setting_cdn.amazon_notes_placeholder')),
                ])->collapsible(),

                Section::make(__('setting_cdn.cloudflare__title'))->schema([

                    Toggle::make('cloudflare.enabled')
                        ->label(__('setting_cdn.cloudflare_enabled'))
                        ->default(false), // Mặc định bật

                    TextInput::make('cloudflare.r2_key')
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->label(__('setting_cdn.cloudflare_r2_key'))
                        ->helperText(__('setting_cdn.cloudflare_r2_key_helper')),

                    TextInput::make('cloudflare.r2_secret')
                        ->revealable()
                        ->password()
                        ->autocomplete('new-password')
                        ->label(__('setting_cdn.cloudflare_r2_secret'))
                        ->helperText(__('setting_cdn.cloudflare_r2_secret_helper')),

                    TextInput::make('cloudflare.r2_region')
                        ->label(__('setting_cdn.cloudflare_r2_region'))
                        ->default('us-east-1')
                        ->helperText(__('setting_cdn.cloudflare_r2_region_helper')),

                    TextInput::make('cloudflare.r2_bucket')
                        ->label(__('setting_cdn.cloudflare_r2_bucket'))
                        ->helperText(__('setting_cdn.cloudflare_r2_bucket_helper')),

                    TextInput::make('cloudflare.r2_url')
                        ->label(__('setting_cdn.cloudflare_r2_url'))
                        ->helperText(__('setting_cdn.cloudflare_r2_url_helper')),

                    TextInput::make('cloudflare.r2_endpoint')
                        ->label(__('setting_cdn.cloudflare_r2_endpoint'))
                        ->helperText(__('setting_cdn.cloudflare_r2_endpoint_helper')),
                ])->collapsed(),
            ])->statePath('data');
    }


    public function save(): void
    {

        try {
            $data = $this->form->getState();

            ZiConfig::mergeAndSaveConfig(ConfigEnum::CDN_SETTING, $data);
            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();

        } catch (Halt $exception) {
            Notification::make()
                ->danger()
                ->title(__('Error'))
                ->body($exception->getMessage())
                ->send();
        }

    }


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];

    }

}
