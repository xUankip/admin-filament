<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\ConfigEnum;
use App\Filament\Clusters\Settings;
use App\Models\System\ZiConfig;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Exceptions\Halt;
use Rawilk\FilamentPasswordInput\Password;

class EmailSetting extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $cluster = Settings::class;
    protected static string $view = 'pages.settings.base-setting';
    protected static ?string $slug = 'email';
    protected static ?int $navigationSort = 44;

    public static function getNavigationLabel(): string
    {
        return __('nav.email_setting');
    }


    public ?array $data = [];



    public function mount(): void
    {
        $config = ZiConfig::getConfigAndParserValue(ConfigEnum::EMAIL_PROVIDER_SETTING);
        $this->form->fill($config);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('smtp.sender_email')
                        ->label(__('setting_email.sender_email'))
                        ->helperText(__('setting_email.sender_email_helper'))
                        ->required(),

                    TextInput::make('smtp.sender_name')
                        ->label(__('setting_email.sender_name'))
                        ->helperText(__('setting_email.sender_name_helper')),
                ]),

                Section::make()->schema([
                    TextInput::make('smtp.host')
                        ->label(__('setting_email.smtp_host'))
                        ->helperText(__('setting_email.smtp_host_helper'))
                        ->required(),

                    TextInput::make('smtp.port')
                        ->label(__('setting_email.smtp_port'))
                        ->helperText(__('setting_email.smtp_port_helper'))
                        ->required(),

                    TextInput::make('smtp.account')
                        ->label(__('setting_email.smtp_account'))
                        ->helperText(__('setting_email.smtp_account_helper')),

                    TextInput::make('smtp.password')
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->label(__('setting_email.smtp_password'))
                        ->helperText(__('setting_email.smtp_password_helper'))
                        ->autocomplete(false),

                    Select::make('smtp.encrypt')
                        ->label(__('setting_email.smtp_encrypt'))
                        ->helperText(__('setting_email.smtp_encrypt_helper'))
                        ->options(fn() => [
                            'none' => 'None',
                            'TLS' => 'TLS',
                            'SSL' => 'SSL',
                        ])
                        ->native(true),
                ]),


            ])->statePath('data');
    }

    public function save(): void
    {

        try {
            $data = $this->form->getState();

            ZiConfig::mergeAndSaveConfig(ConfigEnum::EMAIL_PROVIDER_SETTING, $data);
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

            Action::make('test')
                ->button()
                ->outlined()
                ->size(ActionSize::Small)
                ->extraAttributes([
                    'class' => 'ms-auto',
                ])
                ->icon('heroicon-o-arrow-up-right')
                ->label(__('setting_email.smtp_test_button'))
                ->url('https://codezi.pro/smtp-test',true),
        ];
    }

}
