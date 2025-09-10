<?php

namespace App\Filament\Clusters\UserAndRole\Resources;

use App\Filament\Clusters\UserAndRole;
use App\Filament\Clusters\UserAndRole\Resources\UserResource\Actions\DeleteAction;
use App\Filament\Clusters\UserAndRole\Resources\UserResource\Actions\EditAction;
use App\Filament\Clusters\UserAndRole\Resources\UserResource\Pages;
use App\Filament\Clusters\UserAndRole\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action as TableAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';

    protected static ?string $cluster = UserAndRole::class;

    protected static ?string $navigationGroup = '';

    public static function form(Form $form): Form
    {
        $rows = [
            TextInput::make('name')
                ->required()
                ->label(__('db.name')),
            TextInput::make('email')
                ->email()
                ->required()
                ->label(__('Email')),
            TextInput::make('password')
                ->label(__('db.password'))
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(static function ($state, $record) use ($form) {
                    return !empty($state)
                        ? Hash::make($state)
                        : $record->password;
                }),
        ];

        $rows[] = Forms\Components\Select::make('roles')
            ->multiple()
            ->preload()
            ->relationship('roles', 'name')
            ->label(trans('db.role'));

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__("Name"))
                    ->searchable(isGlobal: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(isGlobal: false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->label(trans('db.role'))
                    ->toggleable()
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__("db.created_at"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                TableAction::make('approveStaff')
                    ->label('Approve Staff')
                    ->visible(fn(User $record) => ($record->status === 'staff_pending'))
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        if (! $record->hasAnyRole(['staff_organizer','staff_admin','super_admin'])) {
                            $record->assignRole('staff_organizer');
                        }
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check'),
                DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
