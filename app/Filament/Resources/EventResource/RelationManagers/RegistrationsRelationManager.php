<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    protected static ?string $recordTitleAttribute = 'user.name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'attended' => 'Attended',
                        'no_show' => 'No Show',
                    ])
                    ->required()
                    ->default('pending'),

                Forms\Components\DateTimePicker::make('registered_at')
                    ->default(now()),

                Forms\Components\Textarea::make('notes')
                    ->maxLength(500),

                Forms\Components\Toggle::make('checked_in')
                    ->label('Checked In')
                    ->default(false),

                Forms\Components\DateTimePicker::make('checked_in_at')
                    ->label('Check-in Time'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.detail.department.name')
                    ->label('Department')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'attended' => 'info',
                        'no_show' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('checked_in')
                    ->boolean()
                    ->label('Checked In'),

                Tables\Columns\TextColumn::make('registered_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Registered'),

                Tables\Columns\TextColumn::make('checked_in_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Check-in Time'),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'attended' => 'Attended',
                        'no_show' => 'No Show',
                    ]),

                Tables\Filters\TernaryFilter::make('checked_in')
                    ->label('Checked In'),

                Tables\Filters\Filter::make('registered_at')
                    ->form([
                        Forms\Components\DatePicker::make('registered_from'),
                        Forms\Components\DatePicker::make('registered_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['registered_from'], fn ($query, $date) => $query->whereDate('registered_at', '>=', $date))
                            ->when($data['registered_until'], fn ($query, $date) => $query->whereDate('registered_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                'attended' => 'Attended',
                                'no_show' => 'No Show',
                            ])
                            ->default('pending'),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update([
                            'checked_in' => true,
                            'checked_in_at' => now(),
                            'status' => 'attended'
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !$record->checked_in),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_check_in')
                        ->label('Check In Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'checked_in' => true,
                                    'checked_in_at' => now(),
                                    'status' => 'attended'
                                ]);
                            }
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
