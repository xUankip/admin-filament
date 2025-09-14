<?php

namespace App\Filament\Resources\DepartmentResource\RelationManagers;

use App\Enums\EventStatus;
use App\Models\Category;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->maxLength(255),

                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required(),

                Forms\Components\Select::make('organizer_id')
                    ->label('Organizer')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required(),

                Forms\Components\Textarea::make('co_organizers')
                    ->label('Co-organizers')
                    ->helperText('JSON format'),

                Forms\Components\TextInput::make('venue')
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('start_at')
                    ->required(),

                Forms\Components\DateTimePicker::make('end_at')
                    ->required(),

                Forms\Components\TextInput::make('capacity')
                    ->numeric(),

                Forms\Components\TextInput::make('seats_left')
                    ->numeric(),

                Forms\Components\Toggle::make('waitlist_enabled')
                    ->default(false),

                Forms\Components\TextInput::make('banner_url')
                    ->url()
                    ->maxLength(255),

                Forms\Components\TextInput::make('doc_url')
                    ->url()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options(collect(EventStatus::cases())->pluck('name', 'value'))
                    ->default(EventStatus::Draft->value),

                Forms\Components\Textarea::make('approval_log')
                    ->label('Approval Log')
                    ->helperText('JSON format'),

                Forms\Components\TextInput::make('popularity_score')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->label('Organizer')
                    ->sortable(),

                Tables\Columns\TextColumn::make('venue')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('seats_left')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('waitlist_enabled')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? $state ?? 'Unknown')
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('popularity_score')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(EventStatus::cases())->pluck('name', 'value')),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id')),

                Tables\Filters\Filter::make('start_at')
                    ->form([
                        Forms\Components\DatePicker::make('start_from'),
                        Forms\Components\DatePicker::make('start_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_from'], fn ($query, $date) => $query->whereDate('start_at', '>=', $date))
                            ->when($data['start_until'], fn ($query, $date) => $query->whereDate('start_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
