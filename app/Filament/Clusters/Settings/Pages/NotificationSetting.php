<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\ConfigEnum;
use App\Filament\Clusters\Settings;
use App\Models\System\ZiConfig;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Rawilk\FilamentPasswordInput\Password;

class NotificationSetting extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-s-bell-alert';
    protected static ?string $cluster = Settings::class;
    protected static string $view = 'pages.settings.base-setting';
    protected static ?string $slug = 'notification';
    protected static ?int $navigationSort = 26;

    public static function getNavigationLabel(): string
    {
        return __('nav.notification_setting');
    }

    public ?array $data = [];


    public function mount(): void
    {
        $config = ZiConfig::getConfigAndParserValue(ConfigEnum::NOTIFICATION_SETTING);
        $this->form->fill($config);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('telegram.bot_token')
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->label(__('setting_api.telegram_bot_token'))
                        ->helperText(__('setting_api.telegram_bot_token_helper'))
                        ->placeholder(__('setting_api.telegram_bot_token_placeholder')),
                ]),
                Section::make(__('setting_notification.notification_settings'))->schema([
                    Toggle::make('notifications.telegram')
                        ->label(__('setting_notification.enable_telegram_notifications'))
                        ->helperText(__('setting_notification.enable_telegram_notifications_helper')),

                    Toggle::make('notifications.contact_submission')
                        ->label(__('setting_notification.notify_contact_submission'))
                        ->helperText(__('setting_notification.notify_contact_submission_helper')),

                    Toggle::make('notifications.new_order')
                        ->label(__('setting_notification.notify_new_order'))
                        ->helperText(__('setting_notification.notify_new_order_helper')),

                    Toggle::make('notifications.new_user')
                        ->label(__('setting_notification.notify_new_user'))
                        ->helperText(__('setting_notification.notify_new_user_helper')),

                    Toggle::make('notifications.daily_report')
                        ->label(__('setting_notification.notify_daily_report'))
                        ->helperText(__('setting_notification.notify_daily_report_helper')),
                ]),
            ])->statePath('data');
    }


    public function save(): void
    {

        try {
            $data = $this->form->getState();

            ZiConfig::mergeAndSaveConfig(ConfigEnum::NOTIFICATION_SETTING, $data);
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
