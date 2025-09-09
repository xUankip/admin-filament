<?php

namespace App\Traits;

use App\Models\System\ZiConfig;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Wiz\FilamentExtend\Forms\Components\WizFileUpload;
use Wiz\FilamentExtend\Forms\Components\WizTextArea;
use Wiz\FilamentExtend\Forms\Components\WizTextInput;

/**
 * @property Form $form
 * @property string CONFIG_KEY
 */
trait SettingFromAction
{

    public function mount(): void
    {
        $config = ZiConfig::getConfigAndParserValue($this->CONFIG_KEY);
        $this->form->fill($config);
    }

    public function save(): void
    {

        try {
            $data = $this->form->getState();
            ZiConfig::mergeAndSaveConfig($this->CONFIG_KEY, $data);
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


    public static function seoFormSchema($title = null): array
    {
        if (!$title) {
            $title = __('setting_seo.heading');
        }
        return [
            Section::make($title)->schema([
                WizTextInput::make('title')
                    ->label(__('setting_seo.title'))
                    ->required()
                    ->hintWithRemainChars(80)
                    ->maxLength(80)
                    ->helperText(__('setting_seo.title_helper')),


                WizTextArea::make('meta_description')
                    ->hintWithRemainChars(210)
                    ->label(__('setting_seo.meta_description'))
                    ->required()
                    ->maxLength(210)
                    ->helperText(__('setting_seo.meta_description_helper')),

                WizFileUpload::make('image')
                    ->image()
                    ->downloadable()
                    ->previewable()
                    ->imageEditor()
                    ->label(__('setting_seo.seo_image'))
                    ->helperText(__('setting_seo.seo_image_helper'))
                    ->setUploadFolder('seo',false),

                Textarea::make('custom_script')
                    ->label(__('setting_seo.custom_script'))
                    ->helperText(__('setting_seo.custom_script_helper')),
            ]),
        ];
    }

}
