<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\ConfigEnum;
use App\Filament\Clusters\Settings;
use App\Models\System\ZiConfig;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
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

class SocialSetting extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-c-share';
    protected static ?string $cluster = Settings::class;
    protected static string $view = 'pages.settings.base-setting';
    protected static ?string $slug = 'social';
    protected static ?int $navigationSort = 22;

    public static function getNavigationLabel(): string
    {
        return __('nav.social_setting');
    }

    public ?array $data = [];


    public function mount(): void
    {
        $config = ZiConfig::getConfigAndParserValue(ConfigEnum::WEBSITE_SETTING);
        $this->form->fill($config);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('social.facebook')
                        ->label(__('setting_social.facebook'))
                        ->helperText(__('setting_social.facebook_helper')),

                    TextInput::make('social.youtube')
                        ->label(__('setting_social.youtube'))
                        ->helperText(__('setting_social.youtube_helper')),

                    TextInput::make('social.twitter')
                        ->label(__('setting_social.twitter'))
                        ->helperText(__('setting_social.twitter_helper')),

                    TextInput::make('social.tiktok')
                        ->label(__('setting_social.tiktok'))
                        ->helperText(__('setting_social.tiktok_helper')),
                ]),

                Section::make()->schema([
                    TextInput::make('social.linkedin')
                        ->label(__('setting_social.linkedin'))
                        ->helperText(__('setting_social.linkedin_helper')),

                    TextInput::make('social.instagram')
                        ->label(__('setting_social.instagram'))
                        ->helperText(__('setting_social.instagram_helper')),

                    TextInput::make('social.pinterest')
                        ->label(__('setting_social.pinterest'))
                        ->helperText(__('setting_social.pinterest_helper')),

                    TextInput::make('social.snapchat')
                        ->label(__('setting_social.snapchat'))
                        ->helperText(__('setting_social.snapchat_helper')),

                    TextInput::make('social.discord') // Thêm trường Discord
                    ->label(__('setting_social.discord'))
                        ->helperText(__('setting_social.discord_helper')),
                ])


            ])->statePath('data');
    }

    public function save(): void
    {

        try {
            $data = $this->form->getState();
            ZiConfig::mergeAndSaveConfig(ConfigEnum::WEBSITE_SETTING, $data);
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
