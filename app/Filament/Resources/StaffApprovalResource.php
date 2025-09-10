<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffApprovalResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StaffApprovalResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Approvals';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('role_hint')->label('Requested Role'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'staff_pending' => 'staff_pending',
                    'active' => 'active',
                ])->default('staff_pending'),
            ])
            ->actions([
                Tables\Actions\Action::make('approveOrganizer')
                    ->label('Approve as Organizer')
                    ->visible(fn(User $record) => $record->status === 'staff_pending')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        $record->syncRoles(['staff_organizer']);
                    })
                    ->color('success')
                    ->icon('heroicon-o-check'),
                Tables\Actions\Action::make('approveAdmin')
                    ->label('Approve as Admin')
                    ->visible(fn(User $record) => $record->status === 'staff_pending')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        $record->syncRoles(['staff_admin']);
                    })
                    ->color('primary')
                    ->icon('heroicon-o-shield-check'),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->visible(fn(User $record) => $record->status === 'staff_pending')
                    ->action(fn(User $record) => $record->update(['status' => 'suspended']))
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaffApprovals::route('/'),
        ];
    }
}


