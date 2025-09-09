<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\ConfigEnum;
use App\Filament\Clusters\Settings;
use App\Models\System\ZiConfig;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Exceptions\Halt;
use Rawilk\FilamentPasswordInput\Password;

class EmbedCodeWebsite extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-c-code-bracket';
    protected static ?string $cluster = Settings::class;
    protected static string $view = 'pages.settings.base-setting';
    protected static ?string $slug = 'embed-code';
    protected static ?int $navigationSort = 45;

    public ?array $data = [];
    public ?array $trackingCodes = [];

    public static function getNavigationLabel(): string
    {
        return __('nav.embed_setting');
    }

    public function mount(): void
    {
        $config = ZiConfig::getConfigAndParserValue(ConfigEnum::EMBED_CODE_SETTING);

        $this->trackingCodes = $config['others'] ?? [];
        $this->form->fill($config);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Textarea::make('google')
                        ->label(__('setting_embed.google_title'))
                        ->helperText(__('setting_embed.google_helper'))
                        ->placeholder(__('setting_embed.google_placeholder')),

                    Textarea::make('facebook')
                        ->label(__('setting_embed.facebook_title'))
                        ->helperText(__('setting_embed.facebook_helper'))
                        ->placeholder(__('setting_embed.facebook_placeholder')),

                ]),
                Repeater::make('others')
                    ->label(__('setting_embed.others_title'))
                    ->default([$this->trackingCodes])
                    ->defaultItems(1)
                    ->schema([
                        Textarea::make('code')
                            ->label(__('setting_embed.code_title'))
                            ->helperText(__('setting_embed.code_helper'))
                            ->placeholder(__('setting_embed.code_placeholder')),
                    ])

            ])->statePath('data');
    }


    public function save(): void
    {

        try {
            $data = $this->form->getState();

            ZiConfig::mergeAndSaveConfig(ConfigEnum::EMBED_CODE_SETTING, $data);
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
