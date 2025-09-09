<?php

namespace Wiz\SEO;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class WizSeo
{
    const BASIC     = 'basic';
    const FULL      = 'full';
    const HAS_IMAGE = 'has_image';

    /**
     * @param array|string $only ['title', 'author', 'description','robots','canonical_url','image'], basic|full
     * @return Group
     */
    public static function make(array|string $only = ['title', 'author', 'description']): Group
    {

        if (is_string($only)) {
            if ($only == self::BASIC) {
                $fields = ['title', 'description'];
            } elseif ($only == self::HAS_IMAGE) {
                $fields = ['title', 'description', 'image'];
            } else {
                $fields = ['title', 'description', /*'author',*/ 'robots', 'canonical_url', 'image'];
            }
        } else {
            $fields = $only;
        }

        return Group::make(array_merge(array_flip($fields), Arr::only([

            'title' => TextInput::make('title')
                ->translateLabel()
                ->reactive()
                ->label(__('wiz-seo::translations.title'))
                ->hint(function (?string $state): string {
                    return (string)Str::of(mb_strlen($state))
                        ->append(' / ')
                        ->append(60 . ' ')
                        ->append(Str::of(__('wiz-seo::translations.characters'))->lower());
                })
                ->columnSpan(2),

            'description' => Textarea::make('description')
                ->translateLabel()
                ->label(__('wiz-seo::translations.description'))
                ->hint(function (?string $state): string {
                    return (string)Str::of(mb_strlen($state))
                        ->append(' / ')
                        ->append(160 . ' ')
                        ->append(Str::of(__('wiz-seo::translations.characters'))->lower());
                })
                ->reactive()
                ->columnSpan(2),

            /*'robots' => TextInput::make('robots')
                ->translateLabel()
                ->label(__('wiz-seo::translations.robots'))
                ->columnSpan(2),*/

            'robots' => Select::make('robots')
                ->label(__('wiz-seo::translations.robots'))
                ->options([
                    'index, follow' => 'Index, Follow',    // Tùy chọn này cho phép chỉ số và theo dõi
                    'noindex, nofollow' => 'Noindex, Nofollow',  // Tùy chọn này không cho phép chỉ số và không theo dõi
                    'index, nofollow' => 'Index, Nofollow',  // Tùy chọn này cho phép chỉ số nhưng không theo dõi
                    'noindex, follow' => 'Noindex, Follow',  // Tùy chọn này không cho phép chỉ số nhưng có thể theo dõi
                ])
                ->translateLabel()
                ->columnSpan(2),

            'author' => TextInput::make('author')
                ->translateLabel()
                ->label(__('wiz-seo::translations.author'))
                ->columnSpan(2),

            'canonical_url' => TextInput::make('canonical_url')
                ->translateLabel()
                ->url()
                ->label(__('wiz-seo::translations.canonical_url'))
                ->columnSpan(2),

            'image' => FileUpload::make('image')
                ->image()
                ->getUploadedFileNameForStorageUsing(
                    fn(TemporaryUploadedFile $file): string => (string)str(Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension())
                        ->prepend(Str::slug(config('app.name')) . '-' . Str::random(9) . '-')->lower(),
                )
                ->directory(fn() => 'seo/' . date('Y/m/d'))
                ->columnSpan(2)
                ->imageEditor()


        ], $fields)))
            ->afterStateHydrated(function (Group $component, ?Model $record) use ($fields): void {
                $component->getChildComponentContainer()->fill(
                    $record?->seo?->only($fields) ?: []
                );
            })
            ->statePath('seo')
            ->dehydrated(false)
            ->saveRelationshipsUsing(function (Model $record, array $state) use ($fields): void {
                $state = collect($state)->only($fields)->map(fn($value) => $value ?: null)->all();

                if (!empty($state['image']) && is_array($state['image'])) {
                    /**
                     * "image" => array:1 [▼
                     * "3546372c-fd06-4036-8846-f86c4e528e8c" => "seo/2023/12/13/wizaigenerator-qfuolg2qh-screenshot-2023-12-06-153012.png"
                     * ]
                     */
                    $state['image'] = array_values($state['image'])[0] ?? '';
                }

                if ($record->seo && $record->seo->exists) {
                    $record->seo->update($state);
                } else {
                    $record->seo()->create($state);
                }
            });
    }

    public static function infoList()
    {
        return Section::make(__('wiz-seo::translations.group_heading'))
            ->schema([
                TextEntry::make('seo.title')
                    ->translateLabel()
                    ->label(__('wiz-seo::translations.title')),

                TextEntry::make('seo.description')
                    ->translateLabel()
                    ->label(__('wiz-seo::translations.description')),

                TextEntry::make('seo.author')
                    ->translateLabel()
                    ->label(__('wiz-seo::translations.author')),

                TextEntry::make('seo.canonical_url')
                    ->translateLabel()
                    ->label(__('wiz-seo::translations.canonical_url')),

                TextEntry::make('seo.robots')
                    ->translateLabel()
                    ->label(__('wiz-seo::translations.robots')),

                ImageEntry::make('seo.image')


            ])->collapsed()
            ->collapsible();

    }
}
